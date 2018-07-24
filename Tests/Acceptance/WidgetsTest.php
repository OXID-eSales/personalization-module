<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Tests\Acceptance;

use OxidEsales\TestingLibrary\TestConfig;

class WidgetsTest extends BaseAcceptanceTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->startMinkSession('goutte');
        $this->enableWidgets();
        $this->setWidgetsIds();
    }

    public function testWidgetsArePresentOnStartPage()
    {
        $this->openShop();
        $this->assertElementPresent(static::BARGAIN_ARTICLES_WIDGET_ID);
        $this->assertElementPresent(static::TOP_ARTICLES_WIDGET_ID);
    }

    public function testWidgetIsPresentOnListPage()
    {
        $this->openShop();
        $this->open((new TestConfig)->getShopUrl() . 'en/Test-category/');

        $this->assertElementPresent(static::LIST_WIDGET_ID);
    }

    /**
     * Test is needed to check logic, as category page and manufacturer page uses same controller.
     */
    public function testWidgetIsNotPresentOnManufacturerPage()
    {
        $this->openShop();
        $this->open((new TestConfig)->getShopUrl() . 'en/By-manufacturer/Big-Matsol/');

        $this->assertElementNotPresent(static::LIST_WIDGET_ID);
    }

    public function testWidgetIsPresentOnDetailsPage()
    {
        $this->openShop();
        $this->open((new TestConfig)->getShopUrl() . 'en/Test-category/');
        $this->open((new TestConfig)->getShopUrl() . 'en/Test-category/Test-product.html');

        $this->assertElementPresent(static::CROSS_SELLING_WIDGET_ID);
    }

    public function testWidgetIsPresentOnThankYouPage()
    {
        $this->startMinkSession('selenium');
        $this->openShop();

        $this->loginInFrontend("testing_account@oxid-esales.local", "useruser");
        $this->addToBasket("1000");
        $nextStep = "%CONTINUE_TO_NEXT_STEP%";
        $this->clickAndWait("//button[contains(text(), '{$nextStep}')]");
        $this->clickAndWait("//button[contains(text(), '{$nextStep}')]");
        $this->click("payment_oxidcashondel");
        $this->clickAndWait("//button[contains(text(), '{$nextStep}')]");
        $this->clickAndWait("//form[@id='orderConfirmAgbBottom']//button");

        $this->assertElementPresent(static::THANK_YOU_INFO_WIDGET_ID);
    }
}
