<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EcondaModule\Tests\Integration;

use OxidEsales\Eshop\Core\Field;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Application\Model\Content;
use OxidEsales\EcondaModule\Application\Core\Events;

class EventsTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    public function testDoesSetDefaultEnableWidgetsConfigurationOnActivate()
    {
        Events::onActivate();

        $this->assertEquals(
            false,
            Registry::getConfig()->getConfigParam('blOeEcondaEnableWidgets')
        );
    }

    public function testDoesSetDefaultTrackingShowNoteOnActivate()
    {
        Events::onActivate();

        $this->assertEquals(
            'no',
            Registry::getConfig()->getConfigParam('sOeEcondaTrackingShowNote')
        );
    }

    public function testDoesNotOverwriteAlreadySetTrackingShowNoteOnActivate()
    {
        Registry::getConfig()->setConfigParam('sOeEcondaTrackingShowNote', 'opt_in');

        Events::onActivate();

        $this->assertEquals(
            'opt_in',
            Registry::getConfig()->getConfigParam('sOeEcondaTrackingShowNote')
        );
    }

    public function testDoesSetDefaultExportPathConfigurationOnActivate()
    {
        Events::onActivate();

        $this->assertEquals(
            'export/oeeconda',
            Registry::getConfig()->getConfigParam('sOeEcondaExportPath')
        );
    }

    /**
     * @dataProvider defaultWidgetTemplateConfigurationProvider
     */
    public function testDoesSetDefaultWidgetTemplateConfigurationOnActivate($configParamName, $expectedTemplate)
    {
        Events::onActivate();

        $this->assertEquals(
            $expectedTemplate,
            Registry::getConfig()->getConfigParam($configParamName)
        );
    }

    public function defaultWidgetTemplateConfigurationProvider()
    {
        return [
            'start page bargain articles' => [
                'sOeEcondaWidgetTemplateStartPageBargainArticles',
                'Component/views/list.ejs.html'
            ],
            'start page top articles' => [
                'sOeEcondaWidgetTemplateStartPageTopArticles',
                'Component/views/list.ejs.html'
            ],
            'list page' => [
                'sOeEcondaWidgetTemplateListPage',
                'Component/views/list.ejs.html'
            ],
            'details page' => [
                'sOeEcondaWidgetTemplateDetailsPage',
                'Component/views/list.ejs.html'
            ],
            'thank you page' => [
                'sOeEcondaWidgetTemplateThankYouPage',
                'Component/views/list.ejs.html'
            ],
        ];
    }

    /**
     * @dataProvider customWidgetTemplateConfigurationProvider
     */
    public function testDoesNotOverwriteAlreadySetConfigurationOnActivate($configParamName, $expectedTemplate)
    {
        Registry::getConfig()->setConfigParam($configParamName, $expectedTemplate);

        Events::onActivate();

        $this->assertEquals(
            $expectedTemplate,
            Registry::getConfig()->getConfigParam($configParamName)
        );
    }

    public function customWidgetTemplateConfigurationProvider()
    {
        return [
            'start page bargain articles' => [
                'sOeEcondaWidgetTemplateStartPageBargainArticles',
                'testBargainTemplate'
            ],
            'start page top articles' => [
                'sOeEcondaWidgetTemplateStartPageTopArticles',
                'testTopArticleTemplate'
            ],
            'list page' => [
                'sOeEcondaWidgetTemplateListPage',
                'testListTemplate'
            ],
            'details page' => [
                'sOeEcondaWidgetTemplateDetailsPage',
                'testDetailsTemplate'
            ],
            'thank you page' => [
                'sOeEcondaWidgetTemplateThankYouPage',
                'testThankYouTemplate'
            ],
        ];
    }

    public function testInsertDefaultSnippetForOptIn()
    {
        Events::onActivate();

        $sql = "select oxid from `oxcontents` where oxloadid = 'oeecondaoptin'";
        $result = DatabaseProvider::getDb()->getCol($sql);
        $id = $result[0];

        $content = oxNew(Content::class);
        $content->load($id);

        $this->assertEquals('oeecondaoptin', $content->oxcontents__oxloadid->value);
    }

    public function testDoesNotOverwriteAlreadySetSnippetForOptIn()
    {
        $sql = "delete from `oxcontents` where OXLOADID = 'oeecondaoptin'";
        DatabaseProvider::getDb()->execute($sql);

        $id = Registry::getUtilsObject()->generateUId();
        $content = oxNew(Content::class);
        $content->setId($id);
        $content->oxcontents__oxloadid = new Field('oeecondaoptin');
        $content->oxcontents__oxcontent = new Field('test content');
        $content->save();

        Events::onActivate();

        $content = oxNew(Content::class);
        $content->load($id);

        $this->assertEquals('test content', $content->oxcontents__oxcontent->value);
    }

    public function testInsertDefaultSnippetForOptOut()
    {
        Events::onActivate();

        $sql = "select oxid from `oxcontents` where oxloadid = 'oeecondaoptout'";
        $result = DatabaseProvider::getDb()->getCol($sql);
        $id = $result[0];

        $content = oxNew(Content::class);
        $content->load($id);

        $this->assertEquals('oeecondaoptout', $content->oxcontents__oxloadid->value);
    }

    public function testDoesNotOverwriteAlreadySetSnippetForOptOut()
    {
        $sql = "delete from `oxcontents` where OXLOADID = 'oeecondaoptout'";
        DatabaseProvider::getDb()->execute($sql);

        $id = Registry::getUtilsObject()->generateUId();
        $content = oxNew(Content::class);
        $content->setId($id);
        $content->oxcontents__oxloadid = new Field('oeecondaoptout');
        $content->oxcontents__oxcontent = new Field('test content');
        $content->save();

        Events::onActivate();

        $content = oxNew(Content::class);
        $content->load($id);

        $this->assertEquals('test content', $content->oxcontents__oxcontent->value);
    }

    public function testInsertDefaultSnippetForUpdate()
    {
        Events::onActivate();

        $sql = "select oxid from `oxcontents` where oxloadid = 'oeecondaupdate'";
        $result = DatabaseProvider::getDb()->getCol($sql);
        $id = $result[0];

        $content = oxNew(Content::class);
        $content->load($id);

        $this->assertEquals('oeecondaupdate', $content->oxcontents__oxloadid->value);
    }

    public function testDoesNotOverwriteAlreadySetSnippetForUpdate()
    {
        $sql = "delete from `oxcontents` where OXLOADID = 'oeecondaupdate'";
        DatabaseProvider::getDb()->execute($sql);

        $id = Registry::getUtilsObject()->generateUId();
        $content = oxNew(Content::class);
        $content->setId($id);
        $content->oxcontents__oxloadid = new Field('oeecondaupdate');
        $content->oxcontents__oxcontent = new Field('test content');
        $content->save();

        Events::onActivate();

        $content = oxNew(Content::class);
        $content->load($id);

        $this->assertEquals('test content', $content->oxcontents__oxcontent->value);
    }
}
