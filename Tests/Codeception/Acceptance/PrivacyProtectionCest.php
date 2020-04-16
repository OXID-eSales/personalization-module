<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\PersonalizationModule\Module\Tests\Codeception\Acceptance;

use Codeception\Util\Fixtures;
use OxidEsales\PersonalizationModule\Tests\Codeception\AcceptanceTester;

/**
 * Class PrivacyProtectionCest
 * @package OxidEsales\PersonalizationModule\Tests\Codeception\Acceptance
 */
final class PrivacyProtectionCest
{
    /**
     * @param AcceptanceTester $I
     *
     * @group privacy_protection
     */
    public function testPrivacyProtectionBannerIsShown(AcceptanceTester $I)
    {
        $I->wantToTest('if privacy protection banner is shown');

        $I->updateConfigInDatabase('sOePersonalizationAccountId', Fixtures::get('oePersonalizationAccountId'));
        $I->updateConfigInDatabase('oePersonalizationContainerId', Fixtures::get('econdaARPContainerId'));
        $I->updateConfigInDatabase('oePersonalizationActive', true);

        //check if banner is on start page
        $homePage = $I->openShop();
        $I->waitForElementVisible($homePage->privacyProtectionBanner);

        //check if banner is there when when we switch pages
        $homePage->openCategoryPage("Kiteboarding");
        $I->waitForElementVisible($homePage->privacyProtectionBanner);
    }
}
