<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Application\Controller\Admin\Tab;

use OxidEsales\Eshop\Application\Controller\Admin\ShopConfiguration;
use OxidEsales\PersonalizationModule\Application\Controller\Admin\ConfigurationTrait;
use OxidEsales\PersonalizationModule\Application\Controller\Admin\FileUploadTrait;
use OxidEsales\PersonalizationModule\Application\Factory;
use OxidEsales\PersonalizationModule\Component\File\FileSystem;
use OxidEsales\PersonalizationModule\Component\File\JsFileLocator;
use FileUpload\FileUpload;

/**
 * Tag manager tab controller.
 */
class TagManagerTabController extends ShopConfiguration
{
    use ConfigurationTrait;
    use FileUploadTrait;

    const TRANSLATION_WHEN_FILE_IS_PRESENT = 'OEPERSONALIZATION_MESSAGE_TAG_MANAGER_FILE_IS_PRESENT';

    const TRANSLATION_WHEN_FILE_IS_NOT_PRESENT = 'OEPERSONALIZATION_MESSAGE_TAG_MANAGER_FILE_IS_NOT_PRESENT';

    protected $_sThisTemplate = 'oepersonalization_tag_manager_tab.tpl';

    /**
     * @var FileSystem
     */
    protected $fileSystem;

    /**
     * @var JsFileLocator
     */
    protected $fileLocator;

    /**
     * @var FileUpload
     */
    protected $fileUploader;

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
        $this->_aViewData['sClassMain'] = __CLASS__;
        parent::__construct();
    }
}
