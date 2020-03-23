<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Component\Export;

use League\Csv\Writer;

/**
 * Writes data to CSV file.
 */
class CsvWriter implements WriterInterface
{
    /**
     * @param string $filePath
     * @param array  $dataToExport
     */
    public function write(string $filePath, array $dataToExport)
    {
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $csv = Writer::createFromPath($filePath, 'a+');
        $csv->setDelimiter('|');
        $csv->setEscape('');

        $csv->insertAll($dataToExport);
    }
}
