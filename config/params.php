<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

return [
    // true to export product variants
    'exportVariants' => true,
    // true to also export the base (parent) product, which has variants.
    'exportVariantsParentProduct' => true,
    // export products only from categories defined in this array.
    // If the array will be left empty, all products of all categories will be exported.
    'exportCategories' => [],
    // export products with given minimum stock quantity
    'exportMinStock' => 1,
    // the data will be exported to given directory
    'exportPath' => 'export/oeeconda',
];
