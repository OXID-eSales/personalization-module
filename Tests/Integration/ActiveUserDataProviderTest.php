<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Tests\Integration;

use OxidEsales\PersonalizationModule\Application\Tracking\Helper\ActiveUserDataProvider;
use OxidEsales\Eshop\Application\Model\User;
use \OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\Field;
use OxidEsales\PersonalizationModule\Tests\Helper\UserPreparationTrait;

class ActiveUserDataProviderTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    use UserPreparationTrait;

    public function setUp()
    {
        $this->createUser('userid');
        parent::setUp();
    }

    public function tearDown()
    {
        $this->deleteUser('userid');
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
