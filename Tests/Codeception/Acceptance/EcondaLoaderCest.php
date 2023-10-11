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
final class EcondaLoaderCest
{
    /**
     * @param AcceptanceTester $I
     *
     * @group econda_personalization
     * @group econda_loader
     */
    public function testEcondaLoaderScriptIsLoadedOnEnableTracking(AcceptanceTester $I)
    {
        $I->wantToTest('econda loader script is loaded');

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

        //econda tracking is not enabled and loader is not loaded
        $I->clearShopCache();
        $I->openShop();
        $I->waitForJS("return typeof window.econda.ready == 'undefined';",10);

        $I->loginAdmin();

        //open econda settings
        $I->selectNavigationFrame();
        $I->click('Personalization');
        $I->click('econda');

        //switch to 'analytics' tab
        $I->selectListFrame();
        $I->click('Analytics');

        //fill data in 'analytics' tab
        $I->selectEditFrame();
        $I->checkOption('confbools[blOePersonalizationTracking]');
        $I->click('Save');

        //check if loader is loaded
        $I->clearShopCache();
        $I->openShop();
        $I->waitForJS("return typeof window.econda.ready != 'undefined';",10);
        $I->waitForJS("return (typeof window.econda.ready) == 'function';",10);
    }
}
