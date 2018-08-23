<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

/**
 * IMPORTANT: please do not change this file, otherwise during module update all changes will be lost.
 * To customize parameters please create new file and pass file path as command line parameter.
 * More information can be found in README.md and official documentation.
 */
return [
    // "true" to export product variants
    'exportVariants' => true,
    // "true" to also export the base (parent) product, which has variants.
    'exportVariantsParentProduct' => true,
    // Export products only from categories defined in this array.
    // If the array will be left empty, all products of all categories will be exported.
    'exportCategories' => [],
    // Export products with given minimum stock quantity.
    'exportMinStock' => 0,
    // The data will be exported to given directory.
    'exportPath' => 'export/oepersonalization',
    // Shop id of the shop from which products and categories will be exported.
    'shopId' => 1
];
