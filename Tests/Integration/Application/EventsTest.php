<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Tests\Integration\Application;

use OxidEsales\Eshop\Core\Field;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Application\Model\Content;
use OxidEsales\PersonalizationModule\Application\Core\Events;

class EventsTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    public function testDoesSetDefaultEnableWidgetsConfigurationOnActivate()
    {
        Events::onActivate();

        $this->assertEquals(
            false,
            Registry::getConfig()->getConfigParam('blOePersonalizationEnableWidgets')
        );
    }

    public function testDoesSetDefaultTrackingShowNoteOnActivate()
    {
        Events::onActivate();

        $this->assertEquals(
            'no',
            Registry::getConfig()->getConfigParam('sOePersonalizationTrackingShowNote')
        );
    }

    public function testDoesNotOverwriteAlreadySetTrackingShowNoteOnActivate()
    {
        Registry::getConfig()->setConfigParam('sOePersonalizationTrackingShowNote', 'opt_in');

        Events::onActivate();

        $this->assertEquals(
            'opt_in',
            Registry::getConfig()->getConfigParam('sOePersonalizationTrackingShowNote')
        );
    }

    public function testDoesSetDefaultExportPathConfigurationOnActivate()
    {
        Events::onActivate();

        $this->assertEquals(
            'export/oepersonalization',
            Registry::getConfig()->getConfigParam('sOePersonalizationExportPath')
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
                'sOePersonalizationWidgetTemplateStartPageBargainArticles',
                'modules/oe/personalization/Component/views/list.ejs.html'
            ],
            'start page top articles' => [
                'sOePersonalizationWidgetTemplateStartPageTopArticles',
                'modules/oe/personalization/Component/views/list.ejs.html'
            ],
            'list page' => [
                'sOePersonalizationWidgetTemplateListPage',
                'modules/oe/personalization/Component/views/list.ejs.html'
            ],
            'details page' => [
                'sOePersonalizationWidgetTemplateDetailsPage',
                'modules/oe/personalization/Component/views/list.ejs.html'
            ],
            'thank you page' => [
                'sOePersonalizationWidgetTemplateThankYouPage',
                'modules/oe/personalization/Component/views/list.ejs.html'
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
                'sOePersonalizationWidgetTemplateStartPageBargainArticles',
                'testBargainTemplate'
            ],
            'start page top articles' => [
                'sOePersonalizationWidgetTemplateStartPageTopArticles',
                'testTopArticleTemplate'
            ],
            'list page' => [
                'sOePersonalizationWidgetTemplateListPage',
                'testListTemplate'
            ],
            'details page' => [
                'sOePersonalizationWidgetTemplateDetailsPage',
                'testDetailsTemplate'
            ],
            'thank you page' => [
                'sOePersonalizationWidgetTemplateThankYouPage',
                'testThankYouTemplate'
            ],
        ];
    }

    public function testInsertDefaultSnippetForOptIn()
    {
        Events::onActivate();

        $sql = "select oxid from `oxcontents` where oxloadid = 'oepersonalizationoptin'";
        $result = DatabaseProvider::getDb()->getCol($sql);
        $id = $result[0];

        $content = oxNew(Content::class);
        $content->load($id);

        $this->assertEquals('oepersonalizationoptin', $content->oxcontents__oxloadid->value);
    }

    public function testDoesNotOverwriteAlreadySetSnippetForOptIn()
    {
        $sql = "delete from `oxcontents` where OXLOADID = 'oepersonalizationoptin'";
        DatabaseProvider::getDb()->execute($sql);

        $id = Registry::getUtilsObject()->generateUId();
        $content = oxNew(Content::class);
        $content->setId($id);
        $content->oxcontents__oxloadid = new Field('oepersonalizationoptin');
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

        $sql = "select oxid from `oxcontents` where oxloadid = 'oepersonalizationoptout'";
        $result = DatabaseProvider::getDb()->getCol($sql);
        $id = $result[0];

        $content = oxNew(Content::class);
        $content->load($id);

        $this->assertEquals('oepersonalizationoptout', $content->oxcontents__oxloadid->value);
    }

    public function testDoesNotOverwriteAlreadySetSnippetForOptOut()
    {
        $sql = "delete from `oxcontents` where OXLOADID = 'oepersonalizationoptout'";
        DatabaseProvider::getDb()->execute($sql);

        $id = Registry::getUtilsObject()->generateUId();
        $content = oxNew(Content::class);
        $content->setId($id);
        $content->oxcontents__oxloadid = new Field('oepersonalizationoptout');
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

        $sql = "select oxid from `oxcontents` where oxloadid = 'oepersonalizationupdate'";
        $result = DatabaseProvider::getDb()->getCol($sql);
        $id = $result[0];

        $content = oxNew(Content::class);
        $content->load($id);

        $this->assertEquals('oepersonalizationupdate', $content->oxcontents__oxloadid->value);
    }

    public function testDoesNotOverwriteAlreadySetSnippetForUpdate()
    {
        $sql = "delete from `oxcontents` where OXLOADID = 'oepersonalizationupdate'";
        DatabaseProvider::getDb()->execute($sql);

        $id = Registry::getUtilsObject()->generateUId();
        $content = oxNew(Content::class);
        $content->setId($id);
        $content->oxcontents__oxloadid = new Field('oepersonalizationupdate');
        $content->oxcontents__oxcontent = new Field('test content');
        $content->save();

        Events::onActivate();

        $content = oxNew(Content::class);
        $content->load($id);

        $this->assertEquals('test content', $content->oxcontents__oxcontent->value);
    }
}
