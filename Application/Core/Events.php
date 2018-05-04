<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EcondaModule\Application\Core;

class Events
{
    const MODULE_NAME = 'module:oeeconda';

    /**
     * Execute action on activate event
     */
    public static function onActivate()
    {
        $config = \OxidEsales\Eshop\Core\Registry::getConfig();
        $activeShopId = $config->getShopId();

        foreach (self::getConfVarsSettings() as $confVar) {
            if (array_key_exists('value', $confVar) && !empty($confVar['value'])) {
                $value = $config->getConfigParam($confVar['name']);
                if (empty($value)) {
                    $value = $confVar['value'];
                }
                $config->saveShopConfVar(
                    $confVar['type'],
                    $confVar['name'],
                    $value,
                    $activeShopId,
                    self::MODULE_NAME
                );
            }
        }
    }

    /**
     * Get configuration variables settings.
     *
     * @return array
     */
    protected static function getConfVarsSettings()
    {
        return [
            [
                'group' => 'oeeconda_account',
                'name' => 'sOeEcondaAccountId',
                'type' => 'str',
                'value' => ''
            ],
            [
                'group' => 'oeeconda_account',
                'name' => 'blOeEcondaUseDemoAccount',
                'type' => 'bool',
                'value' => ''
            ],
            [
                'group' => 'oeeconda_account',
                'name' => 'blOeEcondaEnableWidgets',
                'type' => 'bool',
                'value' => 'false'
            ],
            [
                'group' => 'oeeconda_start_page_widgets',
                'name' => 'sOeEcondaWidgetIdStartPageBargainArticles',
                'type' => 'str',
                'value' => ''
            ],
            [
                'group' => 'oeeconda_start_page_widgets',
                'name' => 'sOeEcondaWidgetTemplateStartPageBargainArticles',
                'type' => 'str',
                'value' => 'Component/views/list.ejs.html'
            ],
            [
                'group' => 'oeeconda_start_page_widgets',
                'name' => 'sOeEcondaWidgetIdStartPageTopArticles',
                'type' => 'str',
                'value' => ''
            ],
            [
                'group' => 'oeeconda_start_page_widgets',
                'name' => 'sOeEcondaWidgetTemplateStartPageTopArticles',
                'type' => 'str',
                'value' => 'Component/views/list.ejs.html'
            ],
            [
                'group' => 'oeeconda_list_page_widgets',
                'name' => 'sOeEcondaWidgetIdListPage',
                'type' => 'str',
                'value' => ''
            ],
            [
                'group' => 'oeeconda_list_page_widgets',
                'name' => 'sOeEcondaWidgetTemplateListPage',
                'type' => 'str',
                'value' => 'Component/views/list.ejs.html'
            ],
            [
                'group' => 'oeeconda_details_page_widgets',
                'name' => 'sOeEcondaWidgetIdDetailsPage',
                'type' => 'str',
                'value' => ''
            ],
            [
                'group' => 'oeeconda_details_page_widgets',
                'name' => 'sOeEcondaWidgetTemplateDetailsPage',
                'type' => 'str',
                'value' => 'Component/views/list.ejs.html'
            ],
            [
                'group' => 'oeeconda_thank_you_page_widgets',
                'name' => 'sOeEcondaWidgetIdThankYouPage',
                'type' => 'str',
                'value' => ''
            ],
            [
                'group' => 'oeeconda_thank_you_page_widgets',
                'name' => 'sOeEcondaWidgetTemplateThankYouPage',
                'type' => 'str',
                'value' => 'Component/views/list.ejs.html'
            ],
        ];
    }
}
