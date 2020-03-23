<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Application\Core;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Core\Field;
use OxidEsales\Eshop\Application\Model\Content;

/**
 * Module events while activating/deactivating module.
 */
class Events
{
    const MODULE_NAME = 'module:oepersonalization';

    const MODULE_ID = 'oepersonalization';

    /**
     * Execute action on activate event
     */
    public static function onActivate()
    {
        $config = Registry::getConfig();
        $activeShopId = $config->getShopId();

        foreach (self::getConfVarsSettings() as $confVar) {
            if (array_key_exists('value', $confVar)) {
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

        self::addContentSnippetUpdate();
    }

    /**
     * Add content snippet to update privacy settings
     */
    protected static function addContentSnippetUpdate()
    {
        $textGerman = <<<'EOT'
<div id="oepersonalization-update">
    <h4>Tracking</h4>
    <input type="radio" name="oepersonalization-state" value="ALLOW"> Zulassen
    <input type="radio" name="oepersonalization-state" value="DENY"> Verbieten
    <div><button type="button" class="btn btn-primary">Aktualisieren</button></div>
</div>
EOT;
        $textEnglish = <<<'EOT'
<div id="oepersonalization-update">
    <h4>Tracking</h4>
    <input type="radio" name="oepersonalization-state" value="ALLOW"> Allow
    <input type="radio" name="oepersonalization-state" value="DENY"> Deny
    <div><button type="button" class="btn btn-primary">Update</button></div>
</div>
EOT;
        $sql = "select count(oxid) from `oxcontents` where oxloadid = 'oepersonalizationupdate'";
        $result = DatabaseProvider::getDb()->getCol($sql);
        if ($result[0] == 0) {
            $id = Registry::getUtilsObject()->generateUId();
            $content = oxNew(Content::class);
            $content->setId($id);
            $content->setLanguage(0);
            $content->oxcontents__oxloadid = new Field('oepersonalizationupdate');
            $content->oxcontents__oxtitle = new Field('Privacy Protection-Einstellungen');
            $content->oxcontents__oxcontent = new Field($textGerman);
            $content->save();

            $content->setLanguage(1);
            $content->oxcontents__oxtitle = new Field('Privacy protection settings');
            $content->oxcontents__oxcontent = new Field($textEnglish);
            $content->save();
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
                'group' => 'oepersonalization_account',
                'name' => 'sOePersonalizationAccountId',
                'type' => 'str',
                'value' => ''
            ],
            [
                'group' => 'oepersonalization_account',
                'name' => 'blOePersonalizationUseDemoAccount',
                'type' => 'bool',
                'value' => ''
            ],
            [
                'group' => 'oepersonalization_account',
                'name' => 'blOePersonalizationEnableWidgets',
                'type' => 'bool',
                'value' => 'false'
            ],
            [
                'group' => 'oepersonalization_start_page_widgets',
                'name' => 'sOePersonalizationWidgetIdStartPageBargainArticles',
                'type' => 'str',
                'value' => ''
            ],
            [
                'group' => 'oepersonalization_start_page_widgets',
                'name' => 'sOePersonalizationWidgetTemplateStartPageBargainArticles',
                'type' => 'str',
                'value' => 'modules/oe/personalization/Component/views/list.ejs.html'
            ],
            [
                'group' => 'oepersonalization_start_page_widgets',
                'name' => 'sOePersonalizationWidgetIdStartPageTopArticles',
                'type' => 'str',
                'value' => ''
            ],
            [
                'group' => 'oepersonalization_start_page_widgets',
                'name' => 'sOePersonalizationWidgetTemplateStartPageTopArticles',
                'type' => 'str',
                'value' => 'modules/oe/personalization/Component/views/list.ejs.html'
            ],
            [
                'group' => 'oepersonalization_list_page_widgets',
                'name' => 'sOePersonalizationWidgetIdListPage',
                'type' => 'str',
                'value' => ''
            ],
            [
                'group' => 'oepersonalization_list_page_widgets',
                'name' => 'sOePersonalizationWidgetTemplateListPage',
                'type' => 'str',
                'value' => 'modules/oe/personalization/Component/views/list.ejs.html'
            ],
            [
                'group' => 'oepersonalization_details_page_widgets',
                'name' => 'sOePersonalizationWidgetIdDetailsPage',
                'type' => 'str',
                'value' => ''
            ],
            [
                'group' => 'oepersonalization_details_page_widgets',
                'name' => 'sOePersonalizationWidgetTemplateDetailsPage',
                'type' => 'str',
                'value' => 'modules/oe/personalization/Component/views/list.ejs.html'
            ],
            [
                'group' => 'oepersonalization_thank_you_page_widgets',
                'name' => 'sOePersonalizationWidgetIdThankYouPage',
                'type' => 'str',
                'value' => ''
            ],
            [
                'group' => 'oepersonalization_thank_you_page_widgets',
                'name' => 'sOePersonalizationWidgetTemplateThankYouPage',
                'type' => 'str',
                'value' => 'modules/oe/personalization/Component/views/list.ejs.html'
            ],
            [
                'group' => '',
                'name' => 'blOePersonalizationTracking',
                'type' => 'bool',
                'value' => ''
            ],
            [
                'group' => '',
                'name' => 'blOePersonalizationTagManager',
                'type' => 'bool',
                'value' => ''
            ],
            [
                'group' => 'oepersonalization_export',
                'name' => 'sOePersonalizationExportPath',
                'type' => 'str',
                'value' => 'export/oepersonalization'
            ],
            [
                'group' => '',
                'name' => 'oePersonalizationActive',
                'type' => 'bool',
                'value' => ''
            ],
            [
                'group' => '',
                'name' => 'oePersonalizationContainerId',
                'type' => 'str',
                'value' => ''
            ],
        ];
    }
}
