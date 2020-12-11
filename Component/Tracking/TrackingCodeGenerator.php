<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Component\Tracking;

use \Econda\Tracking;
use Econda\Tracking\OrderProcess;
use Econda\Tracking\Product;
use Econda\Tracking\ProductDetailView;
use Econda\Tracking\Registration;
use Econda\Tracking\Target;
use Econda\Tracking\TransactionProduct;
use OxidEsales\PersonalizationModule\Component\Tracking\SdkExtension\Email;
use OxidEsales\PersonalizationModule\Component\Tracking\SdkExtension\PageView;
use OxidEsales\PersonalizationModule\Component\Tracking\SdkExtension\Promotions;

/**
 * Class responsible for generating Econda tracking script.
 */
class TrackingCodeGenerator
{
    /**
     * @var ActivePageEntityInterface
     */
    protected $activePageEntity;

    /**
     * @var PageView
     */
    protected $pageView;

    /**
     * @param ActivePageEntityInterface $activePageEntity
     */
    public function __construct(ActivePageEntityInterface $activePageEntity)
    {
        $this->activePageEntity = $activePageEntity;
    }

    /**
     * Generates JS script for Econda tracking.
     *
     * @return string
     */
    public function generateCode()
    {
        $this->pageView = $this->initializePageView();
        $this->addCurrentProductDataToPageView();
        $this->addCurrentOrderProcessToPageView();
        $this->addUserRegistrationDataToPageView();
        $this->addTransactionDataToPageView();
        $this->addPromotionsDataToPageView();
        $this->addSearchQueryDataToPageView();
        $this->addLoginUserDataToPageView();
        $this->addCartDataToPageView();
        $this->addContactFormDataToPageView();
        $this->addNewsletterSubscriptionDataToPageView();
        $this->addEmailToPageView();

        $jsCode = '<script type="text/javascript">';
        $jsCode .= 'econda.privacyprotection.setEmos3PrivacySettings();';
        $jsCode .= '</script>';
        $jsCode .= (string) $this->pageView;

        return $jsCode;
    }

    /**
     * @return PageView
     */
    protected function initializePageView()
    {
        $trackingPage = new PageView([
            'siteId' => $this->activePageEntity->getSiteid(),
            'contentLabel' => $this->activePageEntity->getContent(),
            'countryId' => $this->activePageEntity->getCountryid(),
            'pageId' => $this->activePageEntity->getPageid(),
            'langId' => $this->activePageEntity->getLangid(),
        ]);
        return $trackingPage;
    }

    private function addTransactionDataToPageView()
    {
        $billing = $this->activePageEntity->getBilling();
        if ($billing && $this->activePageEntity->getBoughtProducts()) {
            $transactionProducts = [];
            foreach ($this->activePageEntity->getBoughtProducts() as $transactionProductData) {
                $transactionProducts[] = new TransactionProduct($transactionProductData);
            }
            $billingInfo = [
                'number' => $billing[0],
                'customerId' => $billing[1],
                'location' => $billing[2],
                'value' => $billing[3],
                'products' => $transactionProducts,
            ];

            $this->pageView->add(new Tracking\Order($billingInfo));
        }
    }

    /**
     * @see https://docs.econda.de/de/MONDE/data-services/data-model-management/promotions+und+gutscheine.html
     */
    private function addPromotionsDataToPageView()
    {
        if ($this->activePageEntity->getPromotions()) {
            $this->pageView->add(new Promotions(['data' => $this->activePageEntity->getPromotions()]));
        }
    }

    private function addCurrentProductDataToPageView()
    {
        if (!empty($this->activePageEntity->getProductData() && empty($this->activePageEntity->getProductToBasket()))) {
            $product = new Product($this->activePageEntity->getProductData());
            $this->pageView->add(new ProductDetailView($product));
        }
    }

    private function addCurrentOrderProcessToPageView()
    {
        if ($this->activePageEntity->getOrderProcess()) {
            $this->pageView->add(new OrderProcess($this->activePageEntity->getOrderProcess()));
        }
    }

    private function addUserRegistrationDataToPageView()
    {
        if ($this->activePageEntity->getRegisteredUserId()) {
            $this->pageView->add(new Registration($this->activePageEntity->getRegisteredUserId(), $this->activePageEntity->getRegisteredUserResult()));
        }
    }

    private function addSearchQueryDataToPageView()
    {
        $searchQuery = $this->activePageEntity->getSearchQuery();
        if ($this->activePageEntity->getSearchQuery()) {
            $this->pageView->add(new \Econda\Tracking\Search($searchQuery, $this->activePageEntity->getSearchNumberOfHits()));
        }
    }

    private function addLoginUserDataToPageView()
    {
        $loginUserId = $this->activePageEntity->getLoginUserId();
        if ($loginUserId) {
            $this->pageView->add(new \Econda\Tracking\Login($loginUserId, $this->activePageEntity->getLoginResult()));
        }
    }

    private function addCartDataToPageView()
    {
        $productToBasket = $this->activePageEntity->getProductToBasket();
        if (!empty($productToBasket)) {
            $this->pageView->add(new Tracking\ProductAddToCart(new TransactionProduct($productToBasket)));
        }
    }

    private function addContactFormDataToPageView()
    {
        $contactMessage = $this->activePageEntity->getContactsMessage();
        if (!empty($contactMessage)) {
            $target = new Target([
                'group' => 'Kontakt',
                'name' => $contactMessage,
                'value' => 1
            ]);
            $this->pageView->add($target);
        }
    }

    private function addNewsletterSubscriptionDataToPageView()
    {
        $newsletterMessage = $this->activePageEntity->getNewsletterMessage();
        if (!empty($newsletterMessage)) {
            $target = new Target([
                'group' => 'Newsletter',
                'name' => $newsletterMessage,
                'value' => 1
            ]);
            $this->pageView->add($target);
        }
    }

    private function addEmailToPageView()
    {
        $email = $this->activePageEntity->getEmail();
        if (!empty($email)) {
            $this->pageView->add(new Email($email));
        }
    }
}
