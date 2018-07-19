<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EcondaModule\Tests\Integration;

use OxidEsales\EcondaModule\Application\Tracking\Helper\ActiveUserDataProvider;
use OxidEsales\Eshop\Application\Model\User;
use \OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\Field;

class ActiveUserDataProviderTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    public function setUp()
    {
        $user = oxNew(User::class);
        $user->setId('userid');
        $user->oxuser__oxusername = new Field('testemail@oxid-esales.com');
        $user->oxuser__oxpassword = new Field('test');
        $user->oxuser__oxbirthdate = new Field('any');
        $user->save();

        parent::setUp();
    }

    public function tearDown()
    {
        $user = oxNew(User::class);
        $user->load('userid');
        $user->delete();

        parent::tearDown();
    }

    public function testGetLoggedInUserHashedId()
    {
        Registry::getSession()->setVariable('usr', 'userid');

        $this->assertSame($this->getActiveUserDataProvider()->getActiveUserHashedId(), md5('userid'));
    }

    public function testGetLoggedInUserHashedIdWhenUserNotActive()
    {
        $this->assertSame($this->getActiveUserDataProvider()->getActiveUserHashedId(), null);
    }

    public function testGetLoggedInUserHashedEmail()
    {
        Registry::getSession()->setVariable('usr', 'userid');

        $this->assertSame($this->getActiveUserDataProvider()->getActiveUserHashedEmail(), md5('testemail@oxid-esales.com'));
    }

    public function testGetLoggedInUserHashedEmailWhenUserNotActive()
    {
        $this->assertSame($this->getActiveUserDataProvider()->getActiveUserHashedEmail(), null);
    }

    /**
     * @return ActiveUserDataProvider
     */
    protected function getActiveUserDataProvider()
    {
        return oxNew(ActiveUserDataProvider::class);
    }
}
