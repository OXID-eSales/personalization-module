<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Component\Tracking\File\Validator;

use FileUpload\File;
use FileUpload\Validator\Validator;

/**
 * Checks if file haas javascript extension.
 */
class ExtensionValidator implements Validator
{
    const EXTENSION = 'js';

    const EXTENSION_IS_WRONG = 0;

    protected $errorMessages = array(
        self::EXTENSION_IS_WRONG => 'File must have .' . self::EXTENSION . ' extension',
    );

    /**
     * @var string
     */
    private $filePath;

    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

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
        $fileExtension = $this->getFileExtension($this->filePath);
        if ($fileExtension !== static::EXTENSION) {
            $isValid = false;
            $file->error = $this->errorMessages[self::EXTENSION_IS_WRONG];
        }

        return $isValid;
    }

    /**
     * @param string $filePath
     *
     * @return string
     */
    protected function getFileExtension($filePath)
    {
        return pathinfo($filePath, PATHINFO_EXTENSION);
    }
}
