<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Tests\Acceptance;

use OxidEsales\TestingLibrary\TestConfig;

trait WidgetsDontShowCases
{
    public function testWidgetsAreNotPresentOnStartPage()
    {
        $this->assertElementNotVisible(static::BARGAIN_ARTICLES_WIDGET_ID);
        $this->assertElementNotVisible(static::TOP_ARTICLES_WIDGET_ID);
    }

    public function testWidgetIsNotPresentOnListPage()
    {
        $this->open((new TestConfig)->getShopUrl() . 'en/Test-category/');

        $this->assertElementNotVisible(static::LIST_WIDGET_ID);
    }

    public function testWidgetIsNotPresentOnDetailsPage()
    {
        $this->open((new TestConfig)->getShopUrl() . 'en/Test-category/');
        $this->open((new TestConfig)->getShopUrl() . 'en/Test-category/Test-product.html');

        $this->assertElementNotVisible(static::CROSS_SELLING_WIDGET_ID);
    }

    public function testWidgetIsNotPresentOnThankYouPage()
    {
        $this->loginInFrontend("testing_account@oxid-esales.local", "useruser");
        $this->addToBasket("1000");
        $nextStep = "%CONTINUE_TO_NEXT_STEP%";
        $this->clickAndWait("//button[contains(text(), '{$nextStep}')]");
        $this->clickAndWait("//button[contains(text(), '{$nextStep}')]");
        $this->click("payment_oxidcashondel");
        $this->clickAndWait("//button[contains(text(), '{$nextStep}')]");
        $this->clickAndWait("//form[@id='orderConfirmAgbBottom']//button");

        $this->assertElementNotVisible(static::THANK_YOU_INFO_WIDGET_ID);
        $this->logoutFrontend();
    }
}
