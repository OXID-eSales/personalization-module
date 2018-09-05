<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Tests\Unit\Application;

use OxidEsales\PersonalizationModule\Application\Export\Helper\SqlGenerator;
use PHPUnit\Framework\TestCase;

class SqlGeneratorTest extends TestCase
{
    public function sqlGeneratorProvider()
    {
        return [
            [[1, 2, 3], ' and (oxobject2category.oxcatnid = \'1\' or oxobject2category.oxcatnid = \'2\' or oxobject2category.oxcatnid = \'3\')'],
            [[1], ' and (oxobject2category.oxcatnid = \'1\')'],
            [[], ''],
        ];
    }

    /**
     * @param array $categories
     * @param string $query
     * @dataProvider sqlGeneratorProvider
     */
    public function testSqlGeneration($categories, $query)
    {
        $sqlGenerator = new SqlGenerator();
        $this->assertSame($query, $sqlGenerator->makeCategoriesQueryPart($categories));
    }
}
