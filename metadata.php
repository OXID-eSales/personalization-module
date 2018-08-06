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
    'id'           => 'oepersonalization',
    'title'        => 'OXID personalization powered by Econda',
    'description'  => [
        'de' => 'Modul fügt Personalisierungs-Funktionalität hinzu.',
        'en' => 'Module adds personalization functionality.',
    ],
    'thumbnail' => '/out/pictures/logo.png',
    'version' => '1.0.0',
    'author' => 'OXID eSales AG',
    'url' => 'https://www.oxid-esales.com',
    'email' => 'info@oxid-esales.com',
    'extend' => [
        \OxidEsales\Eshop\Core\ViewConfig::class => \OxidEsales\PersonalizationModule\Application\Core\ViewConfig::class,
        \OxidEsales\Eshop\Application\Controller\ArticleListController::class => \OxidEsales\PersonalizationModule\Application\Controller\ArticleListController::class,
        \OxidEsales\Eshop\Application\Component\Widget\ArticleDetails::class => \OxidEsales\PersonalizationModule\Application\Component\Widget\ArticleDetails::class,
        \OxidEsales\Eshop\Application\Controller\ThankYouController::class => \OxidEsales\PersonalizationModule\Application\Controller\ThankYouController::class,
        \OxidEsales\Eshop\Application\Component\Widget\CookieNote::class => \OxidEsales\PersonalizationModule\Application\Component\Widget\CookieNote::class,
    ],
    'controllers' => [
        'oepersonalizationadmin' => \OxidEsales\PersonalizationModule\Application\Controller\Admin\PersonalizationAdminController::class,
        'oepersonalizationgeneral' => \OxidEsales\PersonalizationModule\Application\Controller\Admin\PersonalizationGeneralController::class,
        'oepersonalizationtracking' => \OxidEsales\PersonalizationModule\Application\Controller\Admin\PersonalizationTrackingController::class,
        'oepersonalizationwidgets' => \OxidEsales\PersonalizationModule\Application\Controller\Admin\PersonalizationWidgetsController::class,
        'oepersonalizationemosjsupload' => \OxidEsales\PersonalizationModule\Application\Controller\Admin\EmosJsUploadController::class,
        'oepersonalizationexport' => \OxidEsales\PersonalizationModule\Application\Controller\Admin\GenerateCSVExports::class,
    ],
    'events' => [
        'onActivate'   => \OxidEsales\PersonalizationModule\Application\Core\Events::class . '::onActivate',
    ],
    'templates' => [
        'oepersonalization.tpl' => 'oe/personalization/Application/views/admin/tpl/oepersonalization.tpl',
        'oepersonalizationadmin.tpl' => 'oe/personalization/Application/views/admin/tpl/oepersonalizationadmin.tpl',
        'oepersonalizationgeneral.tpl' => 'oe/personalization/Application/views/admin/tpl/oepersonalizationgeneral.tpl',
        'oepersonalizationtracking.tpl' => 'oe/personalization/Application/views/admin/tpl/oepersonalizationtracking.tpl',
        'oepersonalizationwidgets.tpl' => 'oe/personalization/Application/views/admin/tpl/oepersonalizationwidgets.tpl',
        'oepersonalizationexport.tpl' => 'oe/personalization/Application/views/admin/tpl/oepersonalizationexport.tpl',
        'oepersonalizationcookienote.tpl' => 'oe/personalization/Application/views/widget/header/cookienote.tpl',
        'oepersonalizationexportresult.tpl' => 'oe/personalization/Application/views/admin/tpl/oepersonalizationexportresult.tpl'
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
