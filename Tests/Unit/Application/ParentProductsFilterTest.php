<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Tests\Unit\Application;

use OxidEsales\PersonalizationModule\Application\Export\Filter\ParentProductsFilter;
use PHPUnit\Framework\TestCase;

class ParentProductsFilterTest extends TestCase
{
    public function filteringOutProductsProvider()
    {
        return [
            'nothing to filter' => [
                [
                    [
                        'OXID' => '1',
                        'OXPARENTID' => '2'
                    ],
                    [
                        'OXID' => '4',
                        'OXPARENTID' => '3'
                    ],
                ],
                [
                    [
                        'OXID' => '1',
                        'OXPARENTID' => '2'
                    ],
                    [
                        'OXID' => '4',
                        'OXPARENTID' => '3'
                    ],
                ]
            ],
            'filter out an entry' => [
                [
                    [
                        'OXID' => '1',
                        'OXPARENTID' => ''
                    ],
                    [
                        'OXID' => '4',
                        'OXPARENTID' => '3'
                    ],
                ],
                [
                    [
                        'OXID' => '4',
                        'OXPARENTID' => '3'
                    ],
                ]
            ]
        ];
    }

    /**
     * @param array $dataForFilter
     * @param array $filteredOutData
     * @dataProvider filteringOutProductsProvider
     */
    public function testFilteringOutProducts($dataForFilter, $filteredOutData)
    {
        $filter = new ParentProductsFilter();
        $this->assertSame($filteredOutData, $filter->filterOutParentProducts($dataForFilter));
    }
}
