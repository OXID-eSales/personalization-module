<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EcondaModule\Tests\Acceptance;

class WidgetsDontShowWhenDemoModeEnabledTest extends BaseAcceptanceTestCase
{
    use WidgetsDontShowCases;

    public function setUp()
    {
        parent::setUp();
        $this->openShop();
        $this->activateDemoMode();
        $this->disableWidgets();
    }
}
