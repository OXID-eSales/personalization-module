<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Application\Controller\Admin;

use OxidEsales\PersonalizationModule\Application\Factory;

/**
 * Controller responsible for .js file upload.
 */
class TagManagerJsUploadController extends AbstractUploadController
{
    /**
     * @param null|Factory $factory
     */
    public function __construct($factory = null)
    {
        if (is_null($factory)) {
            $factory = oxNew(Factory::class);
        }
        $this->fileSystem = $factory->makeFileSystem();
        $this->fileLocator = $factory->makeTagManagerJsFileLocator();
        $this->fileUploader = $factory->makeTagManagerFileUploader();
        parent::__construct();
    }
}
