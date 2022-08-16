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
$aModule = [
    'id'           => 'oepersonalization',
    'title'        => 'OXID Personalization powered by Econda',
    'description'  => [
        'de' => 'Modul fügt Personalisierungs-Funktionalität hinzu.',
        'en' => 'Module adds personalization functionality.',
    ],
    'thumbnail' => '/out/pictures/logo.png',
    'version' => '2.1.0',
    'author' => 'OXID eSales AG',
    'url' => 'https://www.oxid-esales.com',
    'email' => 'info@oxid-esales.com',
    'extend' => [
        \OxidEsales\Eshop\Core\ViewConfig::class => \OxidEsales\PersonalizationModule\Application\Core\ViewConfig::class,
        \OxidEsales\Eshop\Application\Controller\ArticleListController::class => \OxidEsales\PersonalizationModule\Application\Controller\ArticleListController::class,
        \OxidEsales\Eshop\Application\Component\Widget\ArticleDetails::class => \OxidEsales\PersonalizationModule\Application\Component\Widget\ArticleDetails::class,
        \OxidEsales\Eshop\Application\Controller\ThankYouController::class => \OxidEsales\PersonalizationModule\Application\Controller\ThankYouController::class,
        \OxidEsales\Eshop\Application\Model\Article::class => \OxidEsales\PersonalizationModule\Application\Model\Product::class,
    ],
    'controllers' => [
        'oepersonalizationadmin' => \OxidEsales\PersonalizationModule\Application\Controller\Admin\Tab\Container\TabsContainerController::class,
        'oepersonalizationgeneraltab' => \OxidEsales\PersonalizationModule\Application\Controller\Admin\Tab\GeneralTabController::class,
        'oepersonalizationtracking' => \OxidEsales\PersonalizationModule\Application\Controller\Admin\Tab\TrackingTabController::class,
        'oepersonalizationwidgets' => \OxidEsales\PersonalizationModule\Application\Controller\Admin\Tab\WidgetsTabController::class,
        'oepersonalizationexport' => \OxidEsales\PersonalizationModule\Application\Controller\Admin\Tab\ExportTabController::class,
        'oepersonalizationgeneral' => \OxidEsales\PersonalizationModule\Application\Controller\Admin\Tab\Container\TabsListController::class,
        'oepersonalizationtagmanager' => \OxidEsales\PersonalizationModule\Application\Controller\Admin\Tab\TagManagerTabController::class,
    ],
    'events' => [
        'onActivate'   => \OxidEsales\PersonalizationModule\Application\Core\Events::class . '::onActivate',
    ],
    'templates' => [
        'oepersonalization_frameset.tpl' => 'oe/personalization/Application/views/admin/tpl/container/oepersonalization_frameset.tpl',
        'oepersonalization_general.tpl' => 'oe/personalization/Application/views/admin/tpl/container/oepersonalization_general.tpl',
        'oepersonalization_general_tab.tpl' => 'oe/personalization/Application/views/admin/tpl/oepersonalization_general_tab.tpl',
        'oepersonalization_tracking_tab.tpl' => 'oe/personalization/Application/views/admin/tpl/oepersonalization_tracking_tab.tpl',
        'oepersonalization_widgets_tab.tpl' => 'oe/personalization/Application/views/admin/tpl/oepersonalization_widgets_tab.tpl',
        'oepersonalization_export_tab.tpl' => 'oe/personalization/Application/views/admin/tpl/oepersonalization_export_tab.tpl',
        'oepersonalization_tag_manager_tab.tpl' => 'oe/personalization/Application/views/admin/tpl/oepersonalization_tag_manager_tab.tpl',
    ],
    'blocks' => [
        [
            'template' => 'layout/base.tpl',
            'block'=>'base_style',
            'file'=>'Application/views/blocks/scripts.tpl'
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
];
