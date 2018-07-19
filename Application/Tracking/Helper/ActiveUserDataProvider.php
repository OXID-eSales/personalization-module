<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EcondaModule\Application\Tracking\Helper;

use OxidEsales\Eshop\Application\Model\User;

/**
 * Class prepares and provides active user data.
 */
class ActiveUserDataProvider
{
    private $user;

    /**
     * Loads active user.
     */
    public function __construct()
    {
        $this->user = oxNew(User::class);
        $this->user->loadActiveUser();
    }

    /**
     * Hashes active user ID and returns it.
     *
     * @return null|string
     */
    public function getActiveUserHashedId()
    {
        $id = null;
        if ($this->isLoaded()) {
            $id = md5($this->user->oxuser__oxid->value);
        }

        return $id;
    }

    /**
     * Hashes active user email and returns it.
     *
     * @return null|string
     */
    public function getActiveUserHashedEmail()
    {
        $email = null;
        if ($this->isLoaded()) {
            $email = md5($this->user->oxuser__oxusername->value);
        }

        return $email;
    }

    /**
     * @return bool
     */
    public function isLoaded()
    {
        return $this->user->isLoaded();
    }
}
