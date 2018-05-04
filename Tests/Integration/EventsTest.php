<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EcondaModule\Tests\Integration;

use \OxidEsales\Eshop\Core\Registry;
use \OxidEsales\EcondaModule\Application\Core\Events;

class EventsTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    public function testDoesSetDefaultShowEcondaConfigurationOnActivate()
    {
        Events::onActivate();

        $this->assertEquals(
            false,
            Registry::getConfig()->getConfigParam('blOeEcondaEnableWidgets')
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
}
