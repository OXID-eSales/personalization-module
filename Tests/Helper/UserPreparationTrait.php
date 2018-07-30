<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Tests\Helper;

use OxidEsales\Eshop\Application\Model\User;
use OxidEsales\Eshop\Core\Field;

trait UserPreparationTrait
{
    public function createUser($userId)
    {
        $user = oxNew(User::class);
        $user->setId($userId);
        $user->oxuser__oxusername = new Field('testemail@oxid-esales.com');
        $user->oxuser__oxpassword = new Field('test');
        $user->oxuser__oxbirthdate = new Field('any');
        $user->save();

        return $user;
    }

    public function deleteUser($userId)
    {
        $user = oxNew(User::class);
        $user->load($userId);
        $user->delete();
    }
}
