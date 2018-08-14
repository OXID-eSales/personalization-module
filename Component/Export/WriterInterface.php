<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Component\Export;

/**
 * Interface contains methods for writing data to export files.
 */
interface WriterInterface
{
    /**
     * @param string $filePath
     * @param array  $dataToExport
     */
    public function write(string $filePath, array $dataToExport);
}
