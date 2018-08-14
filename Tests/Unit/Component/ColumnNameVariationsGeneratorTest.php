<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Tests\Unit\Component;

use OxidEsales\PersonalizationModule\Component\Export\ColumnNameVariationsGenerator;

class ColumnNameVariationsGeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function testGenerationWhenOnlyOneLanguage()
    {
        $generator = new ColumnNameVariationsGenerator(1);
        $this->assertSame(['Test_title'], $generator->generateNames('Test_title'));
    }

    public function testGenerationWhenMultipleLanguages()
    {
        $generator = new ColumnNameVariationsGenerator(3);
        $this->assertSame(
            ['Test_title', 'Test_title_var1', 'Test_title_var2'],
            $generator->generateNames('Test_title')
        );
    }

    public function testGenerationWhenEdgeCaseAndNoLanguages()
    {
        $generator = new ColumnNameVariationsGenerator(0);
        $this->assertSame(
            ['Test_title'],
            $generator->generateNames('Test_title')
        );
    }
}
