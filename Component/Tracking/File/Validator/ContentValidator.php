<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EcondaModule\Component\Tracking\File\Validator;

use FileUpload\File;
use FileUpload\Validator\Validator;
use OxidEsales\EcondaModule\Component\Tracking\File\JsFileLocator;

class ContentValidator implements Validator
{
    const NEEDLE = 'econda';

    const FILE_IS_WRONG = 0;

    /**
     * @var array
     */
    protected $errorMessages = array(
        self::FILE_IS_WRONG => 'Provided file is not ' . JsFileLocator::TRACKING_CODE_FILE_NAME . ' file',
    );

    /**
     * @inheritdoc
     */
    public function setErrorMessages(array $messages)
    {
        foreach ($messages as $key => $value) {
            $this->errorMessages[$key] = $value;
        }
    }

    /**
     * @inheritdoc
     */
    public function validate(File $file, $current_size = null)
    {
        $isValid = true;
        $fileContents = (string) file_get_contents($file);
        if (strpos($fileContents, static::NEEDLE) === false) {
            $isValid = false;
            $file->error = $this->errorMessages[self::FILE_IS_WRONG];
        }

        return $isValid;
    }
}
