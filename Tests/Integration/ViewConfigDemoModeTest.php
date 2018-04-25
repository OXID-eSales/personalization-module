<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EcondaModule\Tests\Integration;

use \OxidEsales\EcondaModule\Application\Core\ViewConfig;
use \OxidEsales\EcondaModule\Component\DemoAccountData;
use \OxidEsales\Eshop\Core\Registry;

class ViewConfigDemoModeTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    public function setUp()
    {
        parent::setUp();
        Registry::getConfig()->setConfigParam('blOeEcondaUseDemoAccount', '1');
    }

    public function testGetAccountId()
    {
        $this->assertEquals(DemoAccountData::getAccountId(), $this->getViewConfig()->oeEcondaGetAccountId());
    }

    public function testGetWidgetIdStartPageBargainArticles()
    {
        $this->assertEquals(DemoAccountData::getStartPageBestOffersWidgetId(), $this->getViewConfig()->oeEcondaGetStartPageBargainArticlesWidgetId());
    }

    public function testGetWidgetIdStartPageTopArticles()
    {
        $this->assertEquals(DemoAccountData::getStartPageBestSellerWidgetId(), $this->getViewConfig()->oeEcondaGetStartPageTopArticlesWidgetId());
    }

    public function testGetWidgetIdListPage()
    {
        $this->assertEquals(DemoAccountData::getListPageWidgetId(), $this->getViewConfig()->oeEcondaGetListPageWidgetId());
    }

    public function testGetWidgetIdDetailsPage()
    {
        $this->assertEquals(DemoAccountData::getDetailsPageWidgetId(), $this->getViewConfig()->oeEcondaGetDetailsPageWidgetId());
    }

    public function testGetWidgetIdThankYouPage()
    {
        $this->assertEquals(DemoAccountData::getThankYouPageWidgetId(), $this->getViewConfig()->oeEcondaGetThankYouPageWidgetId());
    }

    /**
     * @return object|\OxidEsales\Eshop\Core\ViewConfig|ViewConfig
     */
    protected function getViewConfig()
    {
        return oxNew(\OxidEsales\Eshop\Core\ViewConfig::class);
    }
}
