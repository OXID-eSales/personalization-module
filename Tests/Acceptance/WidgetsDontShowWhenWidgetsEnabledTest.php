<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Tests\Acceptance;

class WidgetsDontShowWhenWidgetsEnabledTest extends BaseAcceptanceTestCase
{
    use WidgetsDontShowCases;

    protected function setUp(): void
    {
        parent::setUp();
        $this->openShop();
        $this->enableWidgets();
    }
}
