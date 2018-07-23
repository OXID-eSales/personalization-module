<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Component\Tracking\File;

use Webmozart\PathUtil\Path;

class JsFileLocator
{
    const TRACKING_CODE_DIRECTORY_NAME = 'oepersonalization';

    const TRACKING_CODE_FILE_NAME = 'emos.js';

    /**
     * @var string
     */
    private $documentRootPath;

    /**
     * @var string
     */
    private $applicationUrl;

    /**
     * @param string $documentRootPath
     * @param string $applicationUrl
     */
    public function __construct($documentRootPath, $applicationUrl)
    {
        $this->documentRootPath = $documentRootPath;
        $this->applicationUrl = $applicationUrl;
    }

    /**
     * @return string
     */
    public function getFileName()
    {
        return static::TRACKING_CODE_FILE_NAME;
    }

    public function getDirectoryName()
    {
        return static::TRACKING_CODE_DIRECTORY_NAME;
    }

    /**
     * @return string
     */
    public function getJsDirectoryLocation()
    {
        return Path::join([$this->documentRootPath, $this->getDirectoryName()]);
    }

    /**
     * @return string
     */
    public function getJsFileLocation()
    {
        return Path::join([$this->getJsDirectoryLocation(), $this->getFileName()]);
    }

    public function getJsFileUrl()
    {
        return $this->applicationUrl . '/' . Path::join([$this->getDirectoryName(), $this->getFileName()]);
    }
}
