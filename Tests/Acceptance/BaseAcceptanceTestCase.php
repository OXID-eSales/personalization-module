<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EcondaModule\Tests\Acceptance;

use OxidEsales\Eshop\Core\Config;

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

    protected function deactivateDemoMode()
    {
        $this->callShopSC('oxConfig', null, null, [
            'blOeEcondaUseDemoAccount' => [
                'type' => 'bool',
                'value' => false,
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

    protected function enableWidgets()
    {
        $this->callShopSC('oxConfig', null, null, [
            'blOeEcondaEnableWidgets' => [
                'type' => 'bool',
                'value' => true,
                'module' => 'module:oeeconda'
            ]
        ]);
    }

    protected function disableWidgets()
    {
        $this->callShopSC('oxConfig', null, null, [
            'blOeEcondaEnableWidgets' => [
                'type' => 'bool',
                'value' => false,
                'module' => 'module:oeeconda'
            ]
        ]);
    }

    protected function setWidgetsIds()
    {
        $this->callShopSC('oxConfig', null, null, [
            'sOeEcondaWidgetIdThankYouPage' => [
                'type' => 'string',
                'value' => '1',
                'module' => 'module:oeeconda'
            ]
        ]);
        $this->callShopSC('oxConfig', null, null, [
            'sOeEcondaWidgetIdDetailsPage' => [
                'type' => 'string',
                'value' => '1',
                'module' => 'module:oeeconda'
            ]
        ]);
        $this->callShopSC('oxConfig', null, null, [
            'sOeEcondaWidgetIdListPage' => [
                'type' => 'string',
                'value' => '1',
                'module' => 'module:oeeconda'
            ]
        ]);
        $this->callShopSC('oxConfig', null, null, [
            'sOeEcondaWidgetIdStartPageBargainArticles' => [
                'type' => 'string',
                'value' => '1',
                'module' => 'module:oeeconda'
            ]
        ]);
        $this->callShopSC('oxConfig', null, null, [
            'sOeEcondaWidgetIdStartPageTopArticles' => [
                'type' => 'string',
                'value' => '1',
                'module' => 'module:oeeconda'
            ]
        ]);
    }

    protected function enableTracking()
    {
        $this->callShopSC('oxConfig', null, null, [
            'blOeEcondaTracking' => [
                'type' => 'bool',
                'value' => true,
                'module' => 'module:oeeconda'
            ]
        ]);
    }
}
