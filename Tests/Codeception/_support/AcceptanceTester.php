<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Tests\Codeception;

use OxidEsales\Facts\Facts;
use OxidEsales\PersonalizationModule\Tests\Codeception\Page\Home;
use OxidEsales\Codeception\Admin\AdminLoginPage;
use Codeception\Util\Fixtures;
use OxidEsales\Codeception\Admin\AdminPanel;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
*/
class AcceptanceTester extends \Codeception\Actor
{
    use _generated\AcceptanceTesterActions;

    /**
     * Open shop first page.
     */
    public function openShop()
    {
        $I = $this;
        $homePage = new Home($I);
        $I->amOnPage($homePage->URL);

        return $homePage;
    }

    public function openAdmin(): AdminLoginPage
    {
        $I = $this;
        $adminLogin = new AdminLoginPage($I);
        $I->amOnPage($adminLogin->URL);
        return $adminLogin;
    }

    public function loginAdmin(): AdminPanel
    {
        $facts = new Facts;
        $admin = ('EE' === $facts->getEdition()) ? Fixtures::get('adminUserEE') : Fixtures::get('adminUser');

        $adminPage = $this->openAdmin();

        return $adminPage->login($admin['userLoginName'], $admin['userPassword']);
    }
}
