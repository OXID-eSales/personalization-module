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
     * @param string $documentRootPath
     * @param string $jsFileName
     * @param string $applicationUrl
     */
    public function __construct($documentRootPath, $jsFileName, $applicationUrl)
    {
        $this->documentRootPath = $documentRootPath;
        $this->jsFileName = $jsFileName;
        $this->applicationUrl = $applicationUrl;
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
        return Path::join([$this->documentRootPath, $this->getDirectoryName()]);
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
        return rtrim($this->applicationUrl, '/') . '/' . Path::join([$this->getDirectoryName(), $this->getFileName()]);
    }
}
