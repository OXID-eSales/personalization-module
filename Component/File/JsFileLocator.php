<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Component\File;

use Webmozart\PathUtil\Path;

/**
 * Class responsible for returning location to JS file.
 */
class JsFileLocator
{
    const TRACKING_CODE_DIRECTORY_NAME = 'oepersonalization';

    /**
     * @var string
     */
    private $documentRootPath;

    /**
     * @var string
     */
    private $applicationUrl;

    /**
     * @var string
     */
    private $jsFileName;

    /**
     * @var int
     */
    private $shopId;

    /**
     * @param string $documentRootPath
     * @param string $jsFileName
     * @param string $applicationUrl
     * @param int    $shopId
     */
    public function __construct($documentRootPath, $jsFileName, $applicationUrl, int $shopId)
    {
        $this->documentRootPath = $documentRootPath;
        $this->jsFileName = $jsFileName;
        $this->applicationUrl = $applicationUrl;
        $this->shopId = $shopId;
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return $this->jsFileName;
    }

    /**
     * @return string
     */
    public function getDirectoryName(): string
    {
        return static::TRACKING_CODE_DIRECTORY_NAME;
    }

    /**
     * @return string
     */
    public function getJsDirectoryLocation(): string
    {
        $path = Path::join([$this->documentRootPath, $this->getShopAwareDirectoryPath()]);

        return $path;
    }

    /**
     * @return string
     */
    public function getJsFileLocation(): string
    {
        return Path::join([$this->getJsDirectoryLocation(), $this->getFileName()]);
    }

    /**
     * @return string
     */
    public function getJsFileUrl(): string
    {
        return rtrim($this->applicationUrl, '/') . '/' . Path::join([$this->getShopAwareDirectoryPath(), $this->getTimestampFileName()]);
    }

    /**
    * @return string
    */
    public function getTimestampFileName(): string
    {
        $fileInfo = new \SplFileInfo($this->getJsFileLocation());
        if ($fileInfo->isFile()) {
            $modified  = $fileInfo->getMTime();
        }
        return $modified ? $this->getFileName() . "?" . $modified : $this->getFileName();
    }

    /**
     * @return string
     */
    private function getShopAwareDirectoryPath(): string
    {
        $directory = $this->getDirectoryName();
        if ($this->shopId !== 1) {
            $directory = Path::join([$directory, (string) $this->shopId]);
        }

        return $directory;
    }
}
