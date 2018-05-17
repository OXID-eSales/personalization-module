<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EcondaModule\Tests\Integration;

use \OxidEsales\EcondaModule\Application\Core\ViewConfig;
use OxidEsales\EcondaModule\Application\Factory;
use OxidEsales\EcondaModule\Component\Tracking\File\FileSystem;
use OxidEsales\EcondaModule\Component\Tracking\File\JsFileLocator;
use \OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\UtilsObject;

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

    /**
     * @return object|\OxidEsales\Eshop\Core\ViewConfig|ViewConfig
     */
    protected function getViewConfig()
    {
        return oxNew(\OxidEsales\Eshop\Core\ViewConfig::class);
    }
}
