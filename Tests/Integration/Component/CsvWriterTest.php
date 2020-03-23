<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Tests\Integration\Component;

use OxidEsales\PersonalizationModule\Component\Export\CsvWriter;

/**
 * @covers \OxidEsales\PersonalizationModule\Component\Export\CsvWriter
 */
class CsvWriterTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    public function testFileCreation()
    {
        $rootPath = $this->getPathToVirtualFileSystem();
        $filePath = $rootPath . 'export/oepersonalization/new_file.csv';

        $writer = new CsvWriter();
        $writer->write($filePath, [['Test entry']]);

        $this->assertFileExists($filePath);
    }

    public function testFileFormat()
    {
        $expectedFormat = '"Test header1"|"Test header2"';
        $expectedFormat .= PHP_EOL . '1|"Entry 1"';
        $expectedFormat .= PHP_EOL . '2|"Entry 2"';
        $expectedFormat .= PHP_EOL;

        $data = [
            ['Test header1', 'Test header2'],
            ['1', 'Entry 1'],
            ['2', 'Entry 2'],
        ];

        $rootPath = $this->getPathToVirtualFileSystem();

        $filePath = $rootPath . 'export/oepersonalization/new_file.csv';

        $writer = new CsvWriter();
        $writer->write($filePath, $data);

        $this->assertSame($expectedFormat, file_get_contents($filePath));
    }

    public function testBasicRFC4180Conversions()
    {
        $data = [
            ['Test header1', 'Test header2'],
            ['test', '"test"'],
            ['bl ank', '"bl ank"'],
            ['col"on', '"col"on"']
        ];

        $rootPath = $this->getPathToVirtualFileSystem();
        $filePath = $rootPath . 'export/oepersonalization/new_file.csv';

        $writer = new CsvWriter();
        $writer->write($filePath, $data);

        $expectedCsv = [
            '"Test header1"|"Test header2"',
            'test|"""test"""',
            '"bl ank"|"""bl ank"""',
            '"col""on"|"""col""on"""'
        ];

        $lines = array_map('trim', file($filePath));
        $this->assertSame($expectedCsv, $lines);
    }

    /**
     * @return string
     */
    private function getPathToVirtualFileSystem(): string
    {
        $vfsWrapper = $this->getVfsStreamWrapper();
        $vfsWrapper->createStructure(['export' => ['oepersonalization' => ['products.csv' => '']]]);
        $rootPath = $vfsWrapper->getRootPath();

        return $rootPath;
    }
}
