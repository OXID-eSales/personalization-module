<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Tests\Acceptance;

use OxidEsales\TestingLibrary\TestConfig;

class WidgetsDemoModeTest extends BaseAcceptanceTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->activateDemoMode();
        $this->enableWidgets();
    }

    public function testWidgetsArePresentOnStartPage()
    {
        $this->openShop();

        $this->checkIfProductExistsInWidget(static::BARGAIN_ARTICLES_WIDGET_ID, 1);
        $this->checkIfProductExistsInWidget(static::BARGAIN_ARTICLES_WIDGET_ID, 2);
        $this->checkIfProductExistsInWidget(static::BARGAIN_ARTICLES_WIDGET_ID, 3);
        $this->checkIfProductExistsInWidget(static::BARGAIN_ARTICLES_WIDGET_ID, 4);

        $this->checkIfProductExistsInWidget(static::TOP_ARTICLES_WIDGET_ID, 1);
        $this->checkIfProductExistsInWidget(static::TOP_ARTICLES_WIDGET_ID, 2);
        $this->checkIfProductExistsInWidget(static::TOP_ARTICLES_WIDGET_ID, 3);
        $this->checkIfProductExistsInWidget(static::TOP_ARTICLES_WIDGET_ID, 4);
    }

    public function testWidgetIsPresentOnListPage()
    {
        $this->openShop();
        $this->open((new TestConfig)->getShopUrl() . 'en/Test-category/');

        $this->checkIfProductExistsInWidget(static::LIST_WIDGET_ID, 1);
        $this->checkIfProductExistsInWidget(static::LIST_WIDGET_ID, 2);
        $this->checkIfProductExistsInWidget(static::LIST_WIDGET_ID, 3);
        $this->checkIfProductExistsInWidget(static::LIST_WIDGET_ID, 4);
    }

    public function testWidgetIsPresentOnDetailsPage()
    {
        $this->openShop();
        $this->open((new TestConfig)->getShopUrl() . 'en/Test-category/');
        $this->open((new TestConfig)->getShopUrl() . 'en/Test-category/Test-product.html');

        $this->checkIfProductExistsInWidget(static::CROSS_SELLING_WIDGET_ID, 1);
        $this->checkIfProductExistsInWidget(static::CROSS_SELLING_WIDGET_ID, 2);
        $this->checkIfProductExistsInWidget(static::CROSS_SELLING_WIDGET_ID, 3);
        $this->checkIfProductExistsInWidget(static::CROSS_SELLING_WIDGET_ID, 4);
    }

    public function testWidgetIsPresentOnThankYouPage()
    {
        $this->openShop();

        $this->loginInFrontend("testing_account@oxid-esales.local", "useruser");
        $this->addToBasket("1000");
        $nextStep = "%CONTINUE_TO_NEXT_STEP%";
        $this->clickAndWait("//button[contains(text(), '{$nextStep}')]");
        $this->clickAndWait("//button[contains(text(), '{$nextStep}')]");
        $this->click("payment_oxidcashondel");
        $this->clickAndWait("//button[contains(text(), '{$nextStep}')]");
        $this->clickAndWait("//form[@id='orderConfirmAgbBottom']//button");

        $this->checkIfProductExistsInWidget(static::THANK_YOU_INFO_WIDGET_ID, 1);
        $this->checkIfProductExistsInWidget(static::THANK_YOU_INFO_WIDGET_ID, 2);
        $this->checkIfProductExistsInWidget(static::THANK_YOU_INFO_WIDGET_ID, 3);
        $this->checkIfProductExistsInWidget(static::THANK_YOU_INFO_WIDGET_ID, 4);
    }
}
