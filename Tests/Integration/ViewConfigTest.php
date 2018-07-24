<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Tests\Integration;

use \OxidEsales\PersonalizationModule\Application\Core\ViewConfig;
use OxidEsales\Eshop\Application\Controller\FrontendController;
use OxidEsales\Eshop\Core\Config;
use \OxidEsales\Eshop\Core\Registry;

class ViewConfigTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    public function testGetAccountId()
    {
        Registry::getConfig()->setConfigParam('sOePersonalizationAccountId', 'testAccountId');
        $this->assertEquals('testAccountId', $this->getViewConfig()->oePersonalizationGetAccountId());
    }

    public function testGetWidgetIdStartPageBargainArticles()
    {
        Registry::getConfig()->setConfigParam('sOePersonalizationWidgetIdStartPageBargainArticles', 'testBargainId');
        Registry::getConfig()->setConfigParam('sOePersonalizationWidgetTemplateStartPageBargainArticles', 'testBargainTemplate');
        $this->assertEquals('testBargainId', $this->getViewConfig()->oePersonalizationGetStartPageBargainArticlesWidgetId());
        $this->assertEquals($this->getConfig()->getShopUrl() . 'testBargainTemplate', $this->getViewConfig()->oePersonalizationGetStartPageBargainArticlesTemplateUrl());
    }

    public function testGetWidgetIdStartPageTopArticles()
    {
        Registry::getConfig()->setConfigParam('sOePersonalizationWidgetIdStartPageTopArticles', 'testTopArticleId');
        Registry::getConfig()->setConfigParam('sOePersonalizationWidgetTemplateStartPageTopArticles', 'testTopArticleTemplate');
        $this->assertEquals('testTopArticleId', $this->getViewConfig()->oePersonalizationGetStartPageTopArticlesWidgetId());
        $this->assertEquals($this->getConfig()->getShopUrl() . 'testTopArticleTemplate', $this->getViewConfig()->oePersonalizationGetStartPageTopArticlesTemplateUrl());
    }

    public function testGetWidgetIdListPage()
    {
        Registry::getConfig()->setConfigParam('sOePersonalizationWidgetIdListPage', 'testListId');
        Registry::getConfig()->setConfigParam('sOePersonalizationWidgetTemplateListPage', 'testListTemplate');
        $this->assertEquals('testListId', $this->getViewConfig()->oePersonalizationGetListPageWidgetId());
        $this->assertEquals($this->getConfig()->getShopUrl() . 'testListTemplate', $this->getViewConfig()->oePersonalizationGetListPageTemplateUrl());
    }

    public function testGetWidgetIdDetailsPage()
    {
        Registry::getConfig()->setConfigParam('sOePersonalizationWidgetIdDetailsPage', 'testDetailsId');
        Registry::getConfig()->setConfigParam('sOePersonalizationWidgetTemplateDetailsPage', 'testDetailsTemplate');
        $this->assertEquals('testDetailsId', $this->getViewConfig()->oePersonalizationGetDetailsPageWidgetId());
        $this->assertEquals($this->getConfig()->getShopUrl() . 'testDetailsTemplate', $this->getViewConfig()->oePersonalizationGetDetailsPageTemplateUrl());
    }

    public function testGetWidgetIdThankYouPage()
    {
        Registry::getConfig()->setConfigParam('sOePersonalizationWidgetIdThankYouPage', 'testThankYouId');
        Registry::getConfig()->setConfigParam('sOePersonalizationWidgetTemplateThankYouPage', 'testThankYouTemplate');
        $this->assertEquals('testThankYouId', $this->getViewConfig()->oePersonalizationGetThankYouPageWidgetId());
        $this->assertEquals($this->getConfig()->getShopUrl() . 'testThankYouTemplate', $this->getViewConfig()->oePersonalizationGetThankYouPageTemplateUrl());
    }

    public function testoePersonalizationEnableWidgets()
    {
        Registry::getConfig()->setConfigParam('blOePersonalizationEnableWidgets', true);
        $this->assertTrue($this->getViewConfig()->oePersonalizationEnableWidgets());
    }

    public function testShowTrackingNote()
    {
        Registry::getConfig()->setConfigParam('sOePersonalizationTrackingShowNote', 'opt_in');
        $this->assertEquals('opt_in', $this->getViewConfig()->oePersonalizationShowTrackingNote());
    }

    public function testGetExportPath()
    {
        Registry::getConfig()->setConfigParam('sOePersonalizationExportPath', 'testExportPath');
        $this->assertEquals('testExportPath', $this->getViewConfig()->oePersonalizationGetExportPath());
    }

    public function testIsLoginAction()
    {
        $this->prepareActiveView('login_noredirect');

        $this->assertTrue($this->getViewConfig()->oePersonalizationIsLoginAction());
    }

    public function testIsLogoutAction()
    {
        $this->prepareActiveView('logout');

        $this->assertTrue($this->getViewConfig()->oePersonalizationIsLogoutAction());
    }

    public function testWhenIsNotLoginAction()
    {
        $this->prepareActiveView('home');

        $this->assertFalse($this->getViewConfig()->oePersonalizationIsLoginAction());
    }

    protected function prepareActiveView($functionName)
    {
        $activeView = $this->getMockBuilder(FrontendController::class)
            ->setMethods(['getFncName'])
            ->getMock();
        $activeView->method('getFncName')->willReturn($functionName);

        $config = new Config();
        $config->setActiveView($activeView);

        Registry::set(Config::class, $config);
    }

    /**
     * @return object|\OxidEsales\Eshop\Core\ViewConfig|ViewConfig
     */
    protected function getViewConfig()
    {
        return oxNew(\OxidEsales\Eshop\Core\ViewConfig::class);
    }
}
