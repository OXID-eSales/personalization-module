<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EcondaModule\Application\Core;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Core\Field;
use OxidEsales\Eshop\Application\Model\Content;

class Events
{
    const MODULE_NAME = 'module:oeeconda';

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
    <a class="oeeconda-optin" href="#" data-dismiss="alert">Tracking zulassen</a>
</div>
EOT;
        $sql = "select count(oxid) from `oxcontents` where oxloadid = 'oeecondaoptin'";
        $result = DatabaseProvider::getDb()->getCol($sql);
        if ($result[0] == 0) {
            $id = Registry::getUtilsObject()->generateUId();
            $content = oxNew(Content::class);
            $content->setId($id);
            $content->oxcontents__oxloadid = new Field('oeecondaoptin');
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
    <a class="oeeconda-optout" href="#" data-dismiss="alert">Tracking verbieten</a>
</div>
EOT;
        $sql = "select count(oxid) from `oxcontents` where oxloadid = 'oeecondaoptout'";
        $result = DatabaseProvider::getDb()->getCol($sql);
        if ($result[0] == 0) {
            $id = Registry::getUtilsObject()->generateUId();
            $content = oxNew(Content::class);
            $content->setId($id);
            $content->oxcontents__oxloadid = new Field('oeecondaoptout');
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
<div id="oeeconda-update">
    <h4>Tracking</h4>
    <input type="radio" name="oeeconda-state" value="ALLOW"> Zulassen
    <input type="radio" name="oeeconda-state" value="DENY"> Verbieten
    <div><button type="button" class="btn btn-primary">Aktualisieren</button></div>
</div>
EOT;
        $sql = "select count(oxid) from `oxcontents` where oxloadid = 'oeecondaupdate'";
        $result = DatabaseProvider::getDb()->getCol($sql);
        if ($result[0] == 0) {
            $id = Registry::getUtilsObject()->generateUId();
            $content = oxNew(Content::class);
            $content->setId($id);
            $content->oxcontents__oxloadid = new Field('oeecondaupdate');
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
            [
                'group' => '',
                'name' => 'blOeEcondaTracking',
                'type' => 'bool',
                'value' => ''
            ],
            [
                'group' => '',
                'name' => 'sOeEcondaTrackingShowNote',
                'type' => 'str',
                'value' => 'no'
            ],
            [
                'group' => 'oeeconda_export',
                'name' => 'sOeEcondaExportPath',
                'type' => 'str',
                'value' => 'export/oeeconda'
            ],
        ];
    }
}
