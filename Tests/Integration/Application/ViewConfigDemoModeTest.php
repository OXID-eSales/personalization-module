<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Tests\Integration\Application;

use \OxidEsales\PersonalizationModule\Application\Core\ViewConfig;
use \OxidEsales\PersonalizationModule\Component\DemoAccountData;
use \OxidEsales\Eshop\Core\Registry;

class ViewConfigDemoModeTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Registry::getConfig()->setConfigParam('blOePersonalizationUseDemoAccount', '1');
    }

    public function testGetAccountId()
    {
        $this->assertEquals(DemoAccountData::getAccountId(), $this->getViewConfig()->oePersonalizationGetAccountId());
    }

    public function testGetWidgetIdStartPageBargainArticles()
    {
        $this->assertEquals(DemoAccountData::getStartPageBestOffersWidgetId(), $this->getViewConfig()->oePersonalizationGetStartPageBargainArticlesWidgetId());
    }

    public function testGetWidgetIdStartPageTopArticles()
    {
        $this->assertEquals(DemoAccountData::getStartPageBestSellerWidgetId(), $this->getViewConfig()->oePersonalizationGetStartPageTopArticlesWidgetId());
    }

    public function testGetWidgetIdListPage()
    {
        $this->assertEquals(DemoAccountData::getListPageWidgetId(), $this->getViewConfig()->oePersonalizationGetListPageWidgetId());
    }

    public function testGetWidgetIdDetailsPage()
    {
        $this->assertEquals(DemoAccountData::getDetailsPageWidgetId(), $this->getViewConfig()->oePersonalizationGetDetailsPageWidgetId());
    }

    public function testGetWidgetIdThankYouPage()
    {
        $this->assertEquals(DemoAccountData::getThankYouPageWidgetId(), $this->getViewConfig()->oePersonalizationGetThankYouPageWidgetId());
    }

    /**
     * @return object|\OxidEsales\Eshop\Core\ViewConfig|ViewConfig
     */
    protected function getViewConfig()
    {
        return oxNew(\OxidEsales\Eshop\Core\ViewConfig::class);
    }
}
