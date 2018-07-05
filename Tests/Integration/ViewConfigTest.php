<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EcondaModule\Tests\Integration;

use \OxidEsales\EcondaModule\Application\Core\ViewConfig;
use OxidEsales\Eshop\Application\Controller\FrontendController;
use OxidEsales\Eshop\Core\Config;
use \OxidEsales\Eshop\Core\Registry;

class ViewConfigTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    public function testGetAccountId()
    {
        Registry::getConfig()->setConfigParam('sOeEcondaAccountId', 'testAccountId');
        $this->assertEquals('testAccountId', $this->getViewConfig()->oeEcondaGetAccountId());
    }

    public function testGetWidgetIdStartPageBargainArticles()
    {
        Registry::getConfig()->setConfigParam('sOeEcondaWidgetIdStartPageBargainArticles', 'testBargainId');
        Registry::getConfig()->setConfigParam('sOeEcondaWidgetTemplateStartPageBargainArticles', 'testBargainTemplate');
        $this->assertEquals('testBargainId', $this->getViewConfig()->oeEcondaGetStartPageBargainArticlesWidgetId());
        $this->assertEquals('testBargainTemplate', $this->getViewConfig()->oeEcondaGetStartPageBargainArticlesTemplate());
    }

    public function testGetWidgetIdStartPageTopArticles()
    {
        Registry::getConfig()->setConfigParam('sOeEcondaWidgetIdStartPageTopArticles', 'testTopArticleId');
        Registry::getConfig()->setConfigParam('sOeEcondaWidgetTemplateStartPageTopArticles', 'testTopArticleTemplate');
        $this->assertEquals('testTopArticleId', $this->getViewConfig()->oeEcondaGetStartPageTopArticlesWidgetId());
        $this->assertEquals('testTopArticleTemplate', $this->getViewConfig()->oeEcondaGetStartPageTopArticlesTemplate());
    }

    public function testGetWidgetIdListPage()
    {
        Registry::getConfig()->setConfigParam('sOeEcondaWidgetIdListPage', 'testListId');
        Registry::getConfig()->setConfigParam('sOeEcondaWidgetTemplateListPage', 'testListTemplate');
        $this->assertEquals('testListId', $this->getViewConfig()->oeEcondaGetListPageWidgetId());
        $this->assertEquals('testListTemplate', $this->getViewConfig()->oeEcondaGetListPageTemplate());
    }

    public function testGetWidgetIdDetailsPage()
    {
        Registry::getConfig()->setConfigParam('sOeEcondaWidgetIdDetailsPage', 'testDetailsId');
        Registry::getConfig()->setConfigParam('sOeEcondaWidgetTemplateDetailsPage', 'testDetailsTemplate');
        $this->assertEquals('testDetailsId', $this->getViewConfig()->oeEcondaGetDetailsPageWidgetId());
        $this->assertEquals('testDetailsTemplate', $this->getViewConfig()->oeEcondaGetDetailsPageTemplate());
    }

    public function testGetWidgetIdThankYouPage()
    {
        Registry::getConfig()->setConfigParam('sOeEcondaWidgetIdThankYouPage', 'testThankYouId');
        Registry::getConfig()->setConfigParam('sOeEcondaWidgetTemplateThankYouPage', 'testThankYouTemplate');
        $this->assertEquals('testThankYouId', $this->getViewConfig()->oeEcondaGetThankYouPageWidgetId());
        $this->assertEquals('testThankYouTemplate', $this->getViewConfig()->oeEcondaGetThankYouPageTemplate());
    }

    public function testoeEcondaEnableWidgets()
    {
        Registry::getConfig()->setConfigParam('blOeEcondaEnableWidgets', true);
        $this->assertTrue($this->getViewConfig()->oeEcondaEnableWidgets());
    }

    public function testShowTrackingNote()
    {
        Registry::getConfig()->setConfigParam('sOeEcondaTrackingShowNote', 'opt_in');
        $this->assertEquals('opt_in', $this->getViewConfig()->oeEcondaShowTrackingNote());
    }

    public function testGetExportPath()
    {
        Registry::getConfig()->setConfigParam('sOeEcondaExportPath', 'testExportPath');
        $this->assertEquals('testExportPath', $this->getViewConfig()->oeEcondaGetExportPath());
    }

    public function testIsLoginAction()
    {
        $this->prepareActiveView('login_noredirect');

        $this->assertTrue($this->getViewConfig()->oeEcondaIsLoginAction());
    }

    public function testIsLogoutAction()
    {
        $this->prepareActiveView('logout');

        $this->assertTrue($this->getViewConfig()->oeEcondaIsLogoutAction());
    }

    public function testWhenIsNotLoginAction()
    {
        $this->prepareActiveView('home');

        $this->assertFalse($this->getViewConfig()->oeEcondaIsLoginAction());
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
