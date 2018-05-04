<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EcondaModule\Application;

use OxidEsales\EcondaModule\Component\Tracking\File\FileSystem;
use OxidEsales\EcondaModule\Component\Tracking\File\JsFileLocator;
use OxidEsales\EcondaModule\Component\Tracking\File\JsFileUploadFactory;
use OxidEsales\Eshop\Core\Registry;

class Factory
{
    /**
     * @return JsFileLocator
     */
    public function getJsFileLocator()
    {
        return oxNew(JsFileLocator::class, Registry::getConfig()->getOutDir());
    }

    /**
     * @return FileSystem
     */
    public function getFileSystem()
    {
        return oxNew(FileSystem::class, oxNew(\Symfony\Component\Filesystem\Filesystem::class));
    }

    /**
     * @return \FileUpload\FileUpload
     */
    public function getFileUploader()
    {
        $jsFileUploadFactory = oxNew(JsFileUploadFactory::class,
            $this->getJsFileLocator()->getJsDirectoryLocation(),
            $this->getJsFileLocator()->getFileName()
        );

        return $jsFileUploadFactory->getFileUploader();
    }
}
