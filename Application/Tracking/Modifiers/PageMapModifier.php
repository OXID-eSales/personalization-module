<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EcondaModule\Application\Tracking\Modifiers;

use OxidEsales\EcondaModule\Application\Tracking\Helper\ActiveControllerCategoryPathBuilder;
use OxidEsales\EcondaModule\Application\Tracking\Helper\CategoryPathBuilder;
use OxidEsales\EcondaModule\Application\Tracking\Page\PageIdentifiers;
use OxidEsales\EcondaModule\Application\Tracking\Page\PageMap;
use OxidEsales\EcondaModule\Application\Tracking\ProductPreparation\ProductTitlePreparator;
use OxidEsales\Eshop\Application\Model\Article;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\Str;

/**
 * Class responsible for modifying pages map which later is used as content for Econda.
 */
class PageMapModifier
{
    /**
     * @var CategoryPathBuilder
     */
    private $categoryPathBuilder;

    /**
     * @var ProductTitlePreparator
     */
    private $productTitlePreparator;

    /**
     * @var ActiveControllerCategoryPathBuilder
     */
    private $activeControllerCategoryPathBuilder;

    /**
     * @var PageIdentifiers
     */
    private $pageIdentifiers;

    /**
     * @var PageMap
     */
    private $pagesMap;

    /**
     * @param CategoryPathBuilder                 $categoryPathBuilder
     * @param ProductTitlePreparator              $productTitlePreparator
     * @param ActiveControllerCategoryPathBuilder $activeControllerCategoryPathBuilder
     * @param PageIdentifiers                     $pageIdentifiers
     * @param PageMap                             $pagesMap
     */
    public function __construct($categoryPathBuilder, $productTitlePreparator, $activeControllerCategoryPathBuilder, $pageIdentifiers, $pagesMap)
    {
        $this->categoryPathBuilder = $categoryPathBuilder;
        $this->productTitlePreparator = $productTitlePreparator;
        $this->activeControllerCategoryPathBuilder = $activeControllerCategoryPathBuilder;
        $this->pageIdentifiers = $pageIdentifiers;
        $this->pagesMap = $pagesMap;
    }

    /**
     * @param array $parameters
     *
     * @return array
     */
    public function modifyPagesMap($parameters)
    {
        $pagesContentMap = $this->pagesMap->getPagesContent();
        $product = (isset($parameters['product']) && $parameters['product']) ? $parameters['product'] : null;
        $currentController = Registry::getConfig()->getActiveView();
        $controllerName = $this->pageIdentifiers->getCurrentControllerName();

        switch ($controllerName) {
            case 'user':
                $pagesContentMap = $this->modifyByUserController($pagesContentMap);
                break;
            case 'oxwarticledetails':
                $pagesContentMap = $this->modifyByOxwArticleDetailsController($pagesContentMap, $product);
                break;
            case 'alist':
                $pagesContentMap = $this->modifyByAListController($pagesContentMap);
                break;
            case 'account':
                $pagesContentMap = $this->modifyByAccountController($pagesContentMap, $currentController->getFncName());
                break;
            case 'contact':
                $pagesContentMap = $this->modifyByContactController($pagesContentMap);
                break;
            case 'newsletter':
                $pagesContentMap = $this->modifyByNewsletterController($pagesContentMap);
                break;
            case 'info':
                $pagesContentMap = $this->modifyByInfoController($pagesContentMap);
                break;
            case 'content':
                $pagesContentMap = $this->modifyByContentController($pagesContentMap, $parameters);
                break;
        }

        return $pagesContentMap;
    }

    /**
     * @param array $pagesContentMap
     *
     * @return array
     */
    protected function modifyByUserController($pagesContentMap)
    {
        $option = Registry::getRequest()->getRequestEscapedParameter('option');
        $option = (isset($option)) ? $option : Registry::getSession()->getVariable('option');

        if (isset($option) && array_key_exists('user_' . $option, $pagesContentMap)) {
            $pagesContentMap['user'] = $pagesContentMap['user_' . $option];
        }

        return $pagesContentMap;
    }

    /**
     * @param array   $pagesContentMap
     * @param Article $product
     * @return mixed
     */
    protected function modifyByOxwArticleDetailsController($pagesContentMap, $product)
    {
        if ($product) {
            $path = $this->categoryPathBuilder->getBasketProductCategoryPath($product);
            $title = $this->productTitlePreparator->prepareProductTitle($product);
            $pagesContentMap['oxwarticledetails'] = "Shop/{$path}/" . strip_tags($title);
        }

        return $pagesContentMap;
    }

    /**
     * @param array $pagesContentMap
     *
     * @return array
     */
    protected function modifyByAListController($pagesContentMap)
    {
        $pagesContentMap['alist'] = 'Shop/' . $this->activeControllerCategoryPathBuilder->getCategoryPath();

        return $pagesContentMap;
    }

    /**
     * @param array  $pagesContentMap
     * @param string $function
     *
     * @return array
     */
    protected function modifyByAccountController($pagesContentMap, $function)
    {
        if ($function) {
            $pagesContentMap['account'] = ($function != 'logout') ? $pagesContentMap['account_login'] : $pagesContentMap['account_logout'];
        } else {
            $pagesContentMap['account'] = $pagesContentMap['account_needlogin'];
        }

        return $pagesContentMap;
    }

    /**
     * @param array $pagesContentMap
     *
     * @return array
     */
    protected function modifyByContactController($pagesContentMap)
    {
        /** @var \OxidEsales\Eshop\Application\Controller\ContactController $currentController */
        $currentController = Registry::getConfig()->getActiveView();
        if ($currentController->getContactSendStatus()) {
            $pagesContentMap['contact'] = $pagesContentMap['contact_success'];
        } else {
            $pagesContentMap['contact'] = $pagesContentMap['contact_failure'];
        }

        return $pagesContentMap;
    }

    /**
     * @param array $pagesContentMap
     *
     * @return mixed
     */
    protected function modifyByNewsletterController($pagesContentMap)
    {
        /** @var \OxidEsales\Eshop\Application\Controller\NewsletterController $currentController */
        $currentController = Registry::getConfig()->getActiveView();
        $pagesContentMap['newsletter'] = $currentController->getNewsletterStatus() ? $pagesContentMap['newsletter_success'] : $pagesContentMap['newsletter_failure'];

        return $pagesContentMap;
    }

    /**
     * @param array $pagesContentMap
     *
     * @return array
     */
    protected function modifyByInfoController($pagesContentMap)
    {
        $templateName = $this->pageIdentifiers->getCurrentTemplateName();
        if (array_key_exists('info_' . $templateName, $pagesContentMap)) {
            $pagesContentMap['info'] = $pagesContentMap['info_' . $templateName];
        } else {
            $pagesContentMap['info'] = 'Content/' . Str::getStr()->preg_replace('/\.tpl$/', '', $templateName);
        }

        return $pagesContentMap;
    }

    /**
     * @param array $pagesContentMap
     * @param array $parameters
     *
     * @return array
     */
    protected function modifyByContentController($pagesContentMap, $parameters)
    {
        /** @var \OxidEsales\Eshop\Application\Controller\ContentController $currentController */
        $currentController = Registry::getConfig()->getActiveView();
        $content = $currentController->getContent();
        $contentId = $content ? $content->oxcontents__oxloadid->value : null;

        if (array_key_exists('content_' . $contentId, $pagesContentMap)) {
            $pagesContentMap['content'] = $pagesContentMap['content_' . $contentId];
        } else {
            $title = isset($parameters['title']) ? $parameters['title'] : null;
            $pagesContentMap['content'] = 'Content/' . $title;
        }

        return $pagesContentMap;
    }
}
