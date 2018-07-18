<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

/**
 * Metadata version
 */
$sMetadataVersion = '2.1';

/**
 * Module information.
 */
$aModule = array(
    'id'           => 'oeeconda',
    'title'        => 'OXID personalization powered by Econda',
    'description'  => [
        'de' => 'Modul fügt Econda Service-Funktionalität hinzu.',
        'en' => 'Module adds Econda service functionality.',
    ],
    'thumbnail' => '/out/pictures/logo.png',
    'version' => '1.0.0',
    'author' => 'OXID eSales AG',
    'url' => 'http://www.oxid-esales.com',
    'email' => 'info@oxid-esales.com',
    'extend' => [
        \OxidEsales\Eshop\Core\ViewConfig::class => \OxidEsales\EcondaModule\Application\Core\ViewConfig::class,
        \OxidEsales\Eshop\Application\Controller\ArticleListController::class => \OxidEsales\EcondaModule\Application\Controller\ArticleListController::class,
        \OxidEsales\Eshop\Application\Component\Widget\ArticleDetails::class => \OxidEsales\EcondaModule\Application\Component\Widget\ArticleDetails::class,
        \OxidEsales\Eshop\Application\Controller\ThankYouController::class => \OxidEsales\EcondaModule\Application\Controller\ThankYouController::class,
    ],
    'controllers' => [
        'oeecondaadmin' => \OxidEsales\EcondaModule\Application\Controller\Admin\EcondaAdminController::class,
        'oeecondaemosjsupload' => \OxidEsales\EcondaModule\Application\Controller\Admin\EmosJsUploadController::class,
        'oeecondaexport' => \OxidEsales\EcondaModule\Application\Feed\GenerateCSVExports::class,
    ],
    'events' => [
        'onActivate'   => '\OxidEsales\EcondaModule\Application\Core\Events::onActivate',
    ],
    'templates' => [
        // Admin Templates
        'oeecondaadmin.tpl' => 'oe/econda/Application/views/admin/tpl/oeecondaadmin.tpl',
        'oeecondaexportresult.tpl' => 'oe/econda/Application/views/admin/tpl/oeecondaexportresult.tpl'
    ],
    'blocks' => [
        [
            'template' => 'layout/base.tpl',
            'block'=>'base_style',
            'file'=>'Application/views/blocks/base_style.tpl'
        ],
        [
            'template' => 'page/list/list.tpl',
            'block'=>'page_list_listhead',
            'file'=>'Application/views/blocks/widgets/page_list_listhead.tpl'
        ],
        [
            'template' => 'page/shop/start.tpl',
            'block'=>'start_bargain_articles',
            'file'=>'Application/views/blocks/widgets/start_bargain_articles.tpl'
        ],
        [
            'template' => 'page/shop/start.tpl',
            'block'=>'start_top_articles',
            'file'=> 'Application/views/blocks/widgets/start_top_articles.tpl'
        ],
        [
            'template' => 'page/details/inc/related_products.tpl',
            'block' => 'details_relatedproducts_crossselling',
            'file'=> 'Application/views/blocks/widgets/details_relatedproducts_crossselling.tpl'
        ],
        [
            'template' => 'page/checkout/thankyou.tpl',
            'block'=>'checkout_thankyou_info',
            'file'=>'Application/views/blocks/widgets/checkout_thankyou_info.tpl'
        ],
    ],
    'smartyPluginDirectories' => [
        'Application/Core/Smarty/Plugin'
    ],
);
