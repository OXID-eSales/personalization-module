<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Component\Tracking\SdkExtension;

use Econda\Tracking\TrackingItemInterface;
use Econda\Util\BaseObject;

/**
 * This is class to construct custom dimension.
 * In this case is email.
 *
 * @property string $email.
 */
class Email extends BaseObject implements TrackingItemInterface
{
    protected $email;
    
    /**
     * Constructor
     * @param type $nameOrPropertiesArray Name of download or an assoc array of property values.
     */
    public function __construct($nameOrPropertiesArray = null)
    {
        if (!empty($nameOrPropertiesArray)) {
            if (is_string($nameOrPropertiesArray)) {
                $this->email = trim($nameOrPropertiesArray);
            } else {
                parent::__construct($nameOrPropertiesArray);
            }
        }
    }

    /**
     * @return array
     */
    public function getTrackingData(): array
    {
        return [
            'hashedvalue' => [[$this->email]],
        ];
    }
}
