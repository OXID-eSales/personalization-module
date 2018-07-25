<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Component\File;

use Webmozart\PathUtil\Path;


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
    public function getFileName()
    {
        return $this->jsFileName;
    }

    /**
     * @return string
     */
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

    /**
     * @return string
     */
    public function getJsFileUrl()
    {
        return $this->applicationUrl . '/' . Path::join([$this->getDirectoryName(), $this->getFileName()]);
    }
}
