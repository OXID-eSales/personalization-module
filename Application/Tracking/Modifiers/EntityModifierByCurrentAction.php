<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EcondaModule\Application\Tracking\Modifiers;

use OxidEsales\EcondaModule\Application\Tracking\Helper\CategoryPathBuilder;
use OxidEsales\EcondaModule\Application\Tracking\Page\PageIdentifiers;
use OxidEsales\EcondaModule\Application\Tracking\ProductPreparation\ProductDataPreparator;
use OxidEsales\EcondaModule\Application\Tracking\ProductPreparation\ProductTitlePreparator;
use OxidEsales\EcondaModule\Component\Tracking\ActivePageEntityInterface;
use OxidEsales\Eshop\Application\Model\Article;
use OxidEsales\Eshop\Application\Model\Basket;
use OxidEsales\Eshop\Application\Model\Order;
use OxidEsales\Eshop\Application\Model\User;
use OxidEsales\Eshop\Core\Registry;
use Smarty;

/**
 * Modifies given tracking code generator object.
 */
class EntityModifierByCurrentAction
{
    /**
     * @var CategoryPathBuilder
     */
    private $categoryPathBuilder;

    /**
     * @var ProductDataPreparator
     */
    private $productDataPreparator;

    /**
     * @var ProductTitlePreparator
     */
    private $productTitlePreparator;

    /**
     * @var PageIdentifiers
     */
    private $pageIdentifiers;

    /**
     * @var ActivePageEntityInterface
     */
    private $activePageEntity;

    /**
     * @param CategoryPathBuilder    $categoryPathBuilder
     * @param ProductDataPreparator  $productDataPreparator
     * @param ProductTitlePreparator $productTitlePreparator
     * @param PageIdentifiers        $pageIdentifiers
     */
    public function __construct($categoryPathBuilder, $productDataPreparator, $productTitlePreparator, $pageIdentifiers)
    {
        $this->categoryPathBuilder = $categoryPathBuilder;
        $this->productDataPreparator = $productDataPreparator;
        $this->productTitlePreparator = $productTitlePreparator;
        $this->pageIdentifiers = $pageIdentifiers;
    }

    /**
     * @param array                     $parameters
     * @param Smarty                    $smarty
     * @param User                      $activeUser
     * @param ActivePageEntityInterface $activePageEntity
     *
     * @return ActivePageEntityInterface
     */
    public function modifyEntity($parameters, $smarty, $activeUser, $activePageEntity)
    {
        $this->activePageEntity = $activePageEntity;
        $product = (isset($parameters['product']) && $parameters['product']) ? $parameters['product'] : null;
        $currentController = Registry::getConfig()->getActiveView();
        $controllerName = $this->pageIdentifiers->getCurrentControllerName();

        switch ($controllerName) {
            case 'payment':
                $this->modifyByPaymentController($activeUser);
                break;
            case 'thankyou':
                /** @var \OxidEsales\Eshop\Application\Controller\ThankYouController $currentController */
                $this->modifyByThankYouController(
                    $currentController->getOrder(),
                    $activeUser,
                    $currentController->getBasket()
                );
                break;
            case 'oxwarticledetails':
                $this->modifyByOxwArticleDetailsController($product);
                break;
            case 'search':
                $this->modifyBySearchController($smarty);
                break;
            case 'contact':
                $this->modifyByContactController();
                break;
            case 'newsletter':
                $this->modifyByNewsletterController();
                break;
            case 'register':
                $this->modifyByRegisterController($activeUser);
                break;
        }

        return $activePageEntity;
    }

    /**
     * @param User $activeUser
     */
    protected function modifyByPaymentController($activeUser)
    {
        if (Registry::getRequest()->getRequestEscapedParameter('new_user')) {
            $this->modifyWithUserRegistrationAction($activeUser);
        }
    }

    /**
     * @param Order  $order
     * @param User   $user
     * @param Basket $basket
     */
    protected function modifyByThankYouController($order, $user, $basket)
    {
        $oConfig = Registry::getConfig();
        $currency = $oConfig->getActShopCurrencyObject();

        $this->activePageEntity->setBilling(
            $order->oxorder__oxordernr->value,
            $user->oxuser__oxusername->value,
            $basket->getPrice()->getBruttoPrice() * (1 / $currency->rate),
            $order->oxorder__oxbillcountry->value,
            $order->oxorder__oxbillzip->value,
            $order->oxorder__oxbillcity->value
        );

        $basketProducts = [];
        $aBasketProducts = $basket->getContents();
        foreach ($aBasketProducts as $oContent) {
            /** @var \OxidEsales\Eshop\Application\Model\BasketItem $oContent */
            $sId = $oContent->getProductId();

            /** @var \OxidEsales\Eshop\Application\Model\Article $oProduct */
            $oProduct = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
            $oProduct->load($sId);

            $sPath = $this->categoryPathBuilder->getBasketProductCategoryPath($oProduct);
            $basketProducts[] = $this->productDataPreparator->prepareForTransaction($oProduct, $sPath, $oContent->getAmount());
        }

        $this->activePageEntity->setBoughtProducts($basketProducts);
    }

    /**
     * @param Article $product
     */
    protected function modifyByOxwArticleDetailsController($product)
    {
        if ($product) {
            $categoryPath = $this->categoryPathBuilder->getBasketProductCategoryPath($product);
            $this->activePageEntity->setProductData($this->productDataPreparator->prepareForDetailsPage($product, $categoryPath));
        }
    }

    /**
     * Sets search page information to entity.
     * Only tracking first search page, not the following pages.
     * #4018: The emospro.search string is URL-encoded forwarded to econda instead of URL-escaped.
     *
     * @param Smarty $smarty
     */
    protected function modifyBySearchController($smarty)
    {
        $page = Registry::getRequest()->getRequestEscapedParameter('pgNr');
        if (!$page) {
            $searchParamForLink = Registry::getRequest()->getRequestParameter('searchparam');
            $searchCount = 0;
            if (($smarty->_tpl_vars['oView']) && $smarty->_tpl_vars['oView']->getArticleCount()) {
                $searchCount = $smarty->_tpl_vars['oView']->getArticleCount();
            }
            $this->activePageEntity->setSearchQuery($searchParamForLink);
            $this->activePageEntity->setSearchNumberOfHits($searchCount);
        }
    }

    /**
     * Sets message if it was sent.
     */
    protected function modifyByContactController()
    {
        /** @var \OxidEsales\Eshop\Application\Controller\ContactController $currentController */
        $currentController = Registry::getConfig()->getActiveView();
        if ($currentController->getContactSendStatus()) {
            $this->activePageEntity->setContactsMessage('Kontaktformular gesendet');
        }
    }

    /**
     * Sets if newsletter have been subscribed.
     */
    protected function modifyByNewsletterController()
    {
        /** @var \OxidEsales\Eshop\Application\Controller\NewsletterController $currentController */
        $currentController = Registry::getConfig()->getActiveView();
        if ($currentController->getNewsletterStatus()) {
            $this->activePageEntity->setNewsletterMessage('Newsletter registriert');
        }
    }

    /**
     * @param User $activeUser
     */
    protected function modifyByRegisterController($activeUser)
    {
        $this->modifyWithUserRegistrationAction($activeUser);
    }

    /**
     * Sets user registration action to an object.
     *
     * @param User $user
     */
    protected function modifyWithUserRegistrationAction($user)
    {
        $errorCode = Registry::getRequest()->getRequestEscapedParameter('newslettererror');
        $successCode = Registry::getRequest()->getRequestEscapedParameter('success');

        if ($errorCode && $errorCode < 0) {
            $this->activePageEntity->setRegisteredUserId($user ? $user->getId() : 'NULL');
            $this->activePageEntity->setRegisteredUserResult(abs($errorCode));
        }

        if ($successCode && $successCode > 0 && $user) {
            $this->activePageEntity->setRegisteredUserId($user->getId());
            $this->activePageEntity->setRegisteredUserResult(0);
        }
    }
}
