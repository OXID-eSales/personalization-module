<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EcondaModule\Tests\Acceptance;

abstract class BaseAcceptanceTestCase extends \OxidEsales\TestingLibrary\AcceptanceTestCase
{
    const BARGAIN_ARTICLES_WIDGET_ID = 'oeEcondaBargainArticles';

    const TOP_ARTICLES_WIDGET_ID = 'oeEcondaTopArticles';

    const LIST_WIDGET_ID = 'oeEcondaListHead';

    const CROSS_SELLING_WIDGET_ID = 'oeEcondaRelatedProductsCrossSelling';

    const THANK_YOU_INFO_WIDGET_ID = 'oeEcondaThankYouInfo';

    protected function activateDemoMode()
    {
        $this->callShopSC('oxConfig', null, null, [
            'blOeEcondaUseDemoAccount' => [
                'type' => 'bool',
                'value' => true,
                'module' => 'module:oeeconda'
            ]
        ]);
    }

    protected function checkIfProductExistsInWidget($widgetSelectorId, $productNumberInRow)
    {
        $this->waitForItemAppear("//div[@id='$widgetSelectorId']//div[@class='row gridView']/div[$productNumberInRow]", 3);
    }

    /**
     * Login in Frontend.
     *
     * @param string $userName     User name (email).
     * @param string $userPass     User password.
     * @param bool   $waitForLogin
     */
    public function loginInFrontend($userName, $userPass, $waitForLogin = true)
    {
        $this->click("//div[contains(@class, 'showLogin')]/button");
        $this->waitForItemAppear("loginBox");
        $this->type("loginEmail", $userName);
        $this->type("loginPasword", $userPass);
        $this->clickAndWait("//div[@id='loginBox']/button");
        if ($waitForLogin) {
            $this->waitForTextDisappear('%LOGIN%');
        }
    }
}
