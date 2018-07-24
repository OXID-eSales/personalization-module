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

        self::addContentSnippetOptIn();
        self::addContentSnippetOptOut();
        self::addContentSnippetUpdate();
    }

    /**
     * Add content snippet for Opt-In case
     */
    protected static function addContentSnippetOptIn()
    {
        $text = <<<'EOT'
Die [FIRMA] erstellt auf dieser Webseite pseudonyme Nutzerprofile auf Basis Ihres Online-Nutzungsverhaltens und nutzt dazu Cookies.
Dieses Profil ermöglicht es uns, Sie online so ausführlich zu beraten, wie es ein Verkäufer in einem persönlichen Gespräch in einem [FIRMA] Store kann.
Weiterhin können wir unser Angebot und unsere Werbung auf Partnerseiten an Ihre persönlichen Bedürfnisse anpassen,
sodass für Sie relevante Produkte angezeigt und uninteressante Angebote ausgeblendet werden.
Die Verarbeitung erfolgt gemäß Art. 6 Abs. 1 lit. f DSGVO und Sie können jederzeit von Ihren Betroffenenrechten Gebrauch machen.
Wenn Sie dies nicht möchten, können Sie hier widersprechen. Wenn Sie unsicher sind, finden Sie hier die gesamten Datenschutzhinweise.
<div>
    <a class="oepersonalization-optin" href="#" data-dismiss="alert">Tracking zulassen</a>
</div>
EOT;
        $sql = "select count(oxid) from `oxcontents` where oxloadid = 'oepersonalizationoptin'";
        $result = DatabaseProvider::getDb()->getCol($sql);
        if ($result[0] == 0) {
            $id = Registry::getUtilsObject()->generateUId();
            $content = oxNew(Content::class);
            $content->setId($id);
            $content->oxcontents__oxloadid = new Field('oepersonalizationoptin');
            $content->oxcontents__oxtitle = new Field('Cookie "Tracking zulassen" Hinweis');
            $content->oxcontents__oxcontent = new Field($text);
            $content->save();
        }
    }

    /**
     * Add content snippet for Opt-Out case
     */
    protected static function addContentSnippetOptOut()
    {
        $text = <<<'EOT'
Die [FIRMA] erstellt auf dieser Webseite pseudonyme Nutzerprofile auf Basis Ihres Online-Nutzungsverhaltens und nutzt dazu Cookies.
Dieses Profil ermöglicht es uns, Sie online so ausführlich zu beraten, wie es ein Verkäufer in einem persönlichen Gespräch in einem [FIRMA] Store kann.
Weiterhin können wir unser Angebot und unsere Werbung auf Partnerseiten an Ihre persönlichen Bedürfnisse anpassen,
sodass für Sie relevante Produkte angezeigt und uninteressante Angebote ausgeblendet werden.
Die Verarbeitung erfolgt gemäß Art. 6 Abs. 1 lit. f DSGVO und Sie können jederzeit von Ihren Betroffenenrechten Gebrauch machen.
Wenn Sie dies nicht möchten, können Sie hier widersprechen. Wenn Sie unsicher sind, finden Sie hier die gesamten Datenschutzhinweise.
<div>
    <a class="oepersonalization-optout" href="#" data-dismiss="alert">Tracking verbieten</a>
</div>
EOT;
        $sql = "select count(oxid) from `oxcontents` where oxloadid = 'oepersonalizationoptout'";
        $result = DatabaseProvider::getDb()->getCol($sql);
        if ($result[0] == 0) {
            $id = Registry::getUtilsObject()->generateUId();
            $content = oxNew(Content::class);
            $content->setId($id);
            $content->oxcontents__oxloadid = new Field('oepersonalizationoptout');
            $content->oxcontents__oxtitle = new Field('Cookie "Tracking verbieten" Hinweis');
            $content->oxcontents__oxcontent = new Field($text);
            $content->save();
        }
    }

    /**
     * Add content snippet to update privacy settings
     */
    protected static function addContentSnippetUpdate()
    {
        $text = <<<'EOT'
<div id="oepersonalization-update">
    <h4>Tracking</h4>
    <input type="radio" name="oepersonalization-state" value="ALLOW"> Zulassen
    <input type="radio" name="oepersonalization-state" value="DENY"> Verbieten
    <div><button type="button" class="btn btn-primary">Aktualisieren</button></div>
</div>
EOT;
        $sql = "select count(oxid) from `oxcontents` where oxloadid = 'oepersonalizationupdate'";
        $result = DatabaseProvider::getDb()->getCol($sql);
        if ($result[0] == 0) {
            $id = Registry::getUtilsObject()->generateUId();
            $content = oxNew(Content::class);
            $content->setId($id);
            $content->oxcontents__oxloadid = new Field('oepersonalizationupdate');
            $content->oxcontents__oxtitle = new Field('Privacy Protection-Einstellungen');
            $content->oxcontents__oxcontent = new Field($text);
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
                'name' => 'sOePersonalizationTrackingShowNote',
                'type' => 'str',
                'value' => 'no'
            ],
            [
                'group' => 'oepersonalization_export',
                'name' => 'sOePersonalizationExportPath',
                'type' => 'str',
                'value' => 'export/oepersonalization'
            ],
        ];
    }
}
