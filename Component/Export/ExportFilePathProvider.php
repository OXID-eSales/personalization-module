<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Component\Export;

/**
 * Class responsible for providing pats to export files.
 */
class ExportFilePathProvider
{
    const PRODUCTS_FILE_NAME = 'products.csv';

    const CATEGORY_FILE_PATH = 'categories.csv';

    /**
     * @var string
     */
    private $rootDirectory;

    /**
     * @param string $rootDirectoryPath
     */
    public function __construct(string $rootDirectoryPath)
    {
        $this->rootDirectory = $rootDirectoryPath;
    }

    /**
     * @param string $relativePathToDirectory
     * @return string
     */
    public function makeDirectoryPath(string $relativePathToDirectory): string
    {
        return rtrim($this->rootDirectory, '/') . '/' . trim($relativePathToDirectory, '/') . '/';
    }

    /**
     * @param string $relativePathToDirectory
     * @return string
     */
    public function makeProductsFilePath(string $relativePathToDirectory)
    {
        return $this->makeDirectoryPath($relativePathToDirectory) . static::PRODUCTS_FILE_NAME;
    }

    /**
     * @param string $relativePathToDirectory
     * @return string
     */
    public function makeCategoriesFilePath(string $relativePathToDirectory)
    {
        return $this->makeDirectoryPath($relativePathToDirectory) . static::CATEGORY_FILE_PATH;
    }
}
