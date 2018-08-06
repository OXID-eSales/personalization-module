<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Tests\Integration\Application;

use OxidEsales\Eshop\Application\Model\User;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\PersonalizationModule\Application\Tracking\Helper\UserActionIdentifier;
use OxidEsales\PersonalizationModule\Application\Tracking\Page\PageIdentifiers;
use OxidEsales\PersonalizationModule\Tests\Helper\ActiveControllerPreparatorTrait;
use OxidEsales\PersonalizationModule\Tests\Helper\UserPreparationTrait;

class UserActionIdentifierTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    use ActiveControllerPreparatorTrait;
    use UserPreparationTrait;

    public function tearDown()
    {
        $this->deleteUser('userid');
        parent::tearDown();
    }

    public function testIsSuccessfulLoginAction()
    {
        $this->prepareRequestAndSessionDataForUserLogin();

        /** @var UserActionIdentifier $userActionIdentifier */
        $userActionIdentifier = oxNew(UserActionIdentifier::class, $this->createUser('userid'), oxNew(PageIdentifiers::class));

        $this->assertTrue($userActionIdentifier->isSuccessfulLogin());
    }

    public function testIsUnsuccessfulLoginActionWhenUserIsNotLoggedIn()
    {
        $this->prepareRequestAndSessionDataForUserLoginWithWrongSessionVariable();

        /** @var UserActionIdentifier $userActionIdentifier */
        $userActionIdentifier = oxNew(UserActionIdentifier::class, $this->createUser('userid'), oxNew(PageIdentifiers::class));

        $this->assertFalse($userActionIdentifier->isSuccessfulLogin());
    }

    public function testIsUnsuccessfulLoginActionWhenWrongFunctionNameInRequest()
    {
        $this->prepareRequestAndSessionDataForUserLoginWithWrongRequest();

        /** @var UserActionIdentifier $userActionIdentifier */
        $userActionIdentifier = oxNew(UserActionIdentifier::class, $this->createUser('userid'), oxNew(PageIdentifiers::class));

        $this->assertFalse($userActionIdentifier->isSuccessfulLogin());
    }

    public function testIsSuccessfulRegister()
    {
        $this->prepareActiveControllerName('register');
        $_POST['success'] = 1;
        Registry::getSession()->setVariable('usr', 'userid');

        $userActionIdentifier = oxNew(UserActionIdentifier::class, $this->createUser('userid'), oxNew(PageIdentifiers::class));

        $this->assertTrue($userActionIdentifier->isSuccessfulRegister());
    }

    public function testUnSuccessfulRegisterWhenWrongRequestValue()
    {
        $this->prepareActiveControllerName('register');
        $_POST['success'] = 0;

        $userActionIdentifier = oxNew(UserActionIdentifier::class, $this->createUser('userid'), oxNew(PageIdentifiers::class));

        $this->assertFalse($userActionIdentifier->isSuccessfulRegister());
    }

    public function testUnSuccessfulRegisterWhenWrongControllerName()
    {
        $this->prepareActiveControllerName('newsletter');
        $_POST['success'] = 1;

        $userActionIdentifier = oxNew(UserActionIdentifier::class, $this->createUser('userid'), oxNew(PageIdentifiers::class));

        $this->assertFalse($userActionIdentifier->isSuccessfulRegister());
    }

    public function testIsSuccessfulLogout()
    {
        $userActionIdentifier = oxNew(UserActionIdentifier::class, oxNew(User::class), oxNew(PageIdentifiers::class));
        $this->prepareActiveControllerToSetFunctionName('logout');

        $this->assertTrue($userActionIdentifier->isSuccessfulLogout());
    }

    public function testIsUnsuccessfulLogout()
    {
        $userActionIdentifier = oxNew(UserActionIdentifier::class, oxNew(User::class), oxNew(PageIdentifiers::class));
        $this->prepareActiveControllerToSetFunctionName('not_logout');

        $this->assertFalse($userActionIdentifier->isSuccessfulLogout());
    }

    public function testIsInStartPage()
    {
        $pageIdentifiersStub = $this->makePageIdentifierStub('start');
        $userActionIdentifier = oxNew(UserActionIdentifier::class, oxNew(User::class), $pageIdentifiersStub);

        $this->assertTrue($userActionIdentifier->isInStartPage());
    }

    public function testIsNotInStartPage()
    {
        $pageIdentifiersStub = $this->makePageIdentifierStub('not_start');
        $userActionIdentifier = oxNew(UserActionIdentifier::class, oxNew(User::class), $pageIdentifiersStub);

        $this->assertFalse($userActionIdentifier->isInStartPage());
    }

    private function prepareRequestAndSessionDataForUserLogin()
    {
        $this->prepareActiveControllerToSetFunctionName('login_noredirect');
        Registry::getSession()->setVariable('usr', 'userid');
    }

    private function prepareRequestAndSessionDataForUserLoginWithWrongSessionVariable()
    {
        $this->prepareActiveControllerToSetFunctionName('login_noredirect');
        Registry::getSession()->setVariable('usr', 'wrong_user');
    }

    private function prepareRequestAndSessionDataForUserLoginWithWrongRequest()
    {
        $this->prepareActiveControllerToSetFunctionName('not_login_noredirect');
        Registry::getSession()->setVariable('usr', 'userid');
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|PageIdentifiers
     */
    private function makePageIdentifierStub($controllerName)
    {
        $pageIdentifiersStub = $this->getMockBuilder(PageIdentifiers::class)
            ->setMethods(['getCurrentControllerName'])
            ->getMock();
        $pageIdentifiersStub->method('getCurrentControllerName')->willReturn($controllerName);
        return $pageIdentifiersStub;
    }
}
