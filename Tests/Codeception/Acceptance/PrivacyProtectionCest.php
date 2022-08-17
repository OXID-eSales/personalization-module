<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\PersonalizationModule\Module\Tests\Codeception\Acceptance;

use Codeception\Util\Fixtures;
use OxidEsales\PersonalizationModule\Tests\Codeception\AcceptanceTester;
use OxidEsales\PersonalizationModule\Tests\Codeception\AcceptanceAdminTester;

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

        $I->loginAdmin();

        //open econda settings
        $I->selectNavigationFrame();
        $I->click('Personalization');
        $I->click('econda');

        //fill data in 'general' tab
        $I->selectEditFrame();
        $I->fillField('confstrs[sOePersonalizationAccountId]', Fixtures::get('oePersonalizationAccountId'));
        $I->fillField('confstrs[oePersonalizationContainerId]', Fixtures::get('econdaARPContainerId'));
        $I->click('Save');

        //switch to 'analytics' tab
        $I->selectListFrame();
        $I->click('Analytics');

        //fill data in 'analytics' tab
        $I->selectEditFrame();
        $I->checkOption('confbools[oePersonalizationActive]');
        $I->click('Save');

        //check if banner is on start page
        $homePage = $I->openShop();
        $I->waitForElementVisible($homePage->privacyProtectionBanner);

        //check if banner is there when when we switch pages
        $I->amOnPage("/en/Kiteboarding");
        $I->waitForElementVisible($homePage->privacyProtectionBanner);
    }
}
