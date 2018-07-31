<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Tests\Integration;

use OxidEsales\Eshop\Core\UtilsObject;
use \OxidEsales\PersonalizationModule\Application\Core\ViewConfig;
use \OxidEsales\Eshop\Core\Registry;
use OxidEsales\PersonalizationModule\Application\Factory;
use OxidEsales\PersonalizationModule\Application\Tracking\Helper\UserActionIdentifier;
use OxidEsales\PersonalizationModule\Tests\Helper\ActiveControllerPreparatorTrait;

class ViewConfigTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    use ActiveControllerPreparatorTrait;

    public function testGetAccountId()
    {
        Registry::getConfig()->setConfigParam('sOePersonalizationAccountId', 'testAccountId');
        $this->assertEquals('testAccountId', $this->getViewConfig()->oePersonalizationGetAccountId());
    }

    public function testGetWidgetIdStartPageBargainArticles()
    {
        Registry::getConfig()->setConfigParam('sOePersonalizationWidgetIdStartPageBargainArticles', 'testBargainId');
        Registry::getConfig()->setConfigParam('sOePersonalizationWidgetTemplateStartPageBargainArticles', 'testBargainTemplate');
        $this->assertEquals('testBargainId', $this->getViewConfig()->oePersonalizationGetStartPageBargainArticlesWidgetId());
        $this->assertEquals($this->getConfig()->getShopUrl() . 'testBargainTemplate', $this->getViewConfig()->oePersonalizationGetStartPageBargainArticlesTemplateUrl());
    }

    public function testGetWidgetIdStartPageTopArticles()
    {
        Registry::getConfig()->setConfigParam('sOePersonalizationWidgetIdStartPageTopArticles', 'testTopArticleId');
        Registry::getConfig()->setConfigParam('sOePersonalizationWidgetTemplateStartPageTopArticles', 'testTopArticleTemplate');
        $this->assertEquals('testTopArticleId', $this->getViewConfig()->oePersonalizationGetStartPageTopArticlesWidgetId());
        $this->assertEquals($this->getConfig()->getShopUrl() . 'testTopArticleTemplate', $this->getViewConfig()->oePersonalizationGetStartPageTopArticlesTemplateUrl());
    }

    public function testGetWidgetIdListPage()
    {
        Registry::getConfig()->setConfigParam('sOePersonalizationWidgetIdListPage', 'testListId');
        Registry::getConfig()->setConfigParam('sOePersonalizationWidgetTemplateListPage', 'testListTemplate');
        $this->assertEquals('testListId', $this->getViewConfig()->oePersonalizationGetListPageWidgetId());
        $this->assertEquals($this->getConfig()->getShopUrl() . 'testListTemplate', $this->getViewConfig()->oePersonalizationGetListPageTemplateUrl());
    }

    public function testGetWidgetIdDetailsPage()
    {
        Registry::getConfig()->setConfigParam('sOePersonalizationWidgetIdDetailsPage', 'testDetailsId');
        Registry::getConfig()->setConfigParam('sOePersonalizationWidgetTemplateDetailsPage', 'testDetailsTemplate');
        $this->assertEquals('testDetailsId', $this->getViewConfig()->oePersonalizationGetDetailsPageWidgetId());
        $this->assertEquals($this->getConfig()->getShopUrl() . 'testDetailsTemplate', $this->getViewConfig()->oePersonalizationGetDetailsPageTemplateUrl());
    }

    public function testGetWidgetIdThankYouPage()
    {
        Registry::getConfig()->setConfigParam('sOePersonalizationWidgetIdThankYouPage', 'testThankYouId');
        Registry::getConfig()->setConfigParam('sOePersonalizationWidgetTemplateThankYouPage', 'testThankYouTemplate');
        $this->assertEquals('testThankYouId', $this->getViewConfig()->oePersonalizationGetThankYouPageWidgetId());
        $this->assertEquals($this->getConfig()->getShopUrl() . 'testThankYouTemplate', $this->getViewConfig()->oePersonalizationGetThankYouPageTemplateUrl());
    }

    public function testoePersonalizationEnableWidgets()
    {
        Registry::getConfig()->setConfigParam('blOePersonalizationEnableWidgets', true);
        $this->assertTrue($this->getViewConfig()->oePersonalizationEnableWidgets());
    }

    public function testShowTrackingNote()
    {
        Registry::getConfig()->setConfigParam('sOePersonalizationTrackingShowNote', 'opt_in');
        $this->assertEquals('opt_in', $this->getViewConfig()->oePersonalizationShowTrackingNote());
    }

    public function testGetExportPath()
    {
        Registry::getConfig()->setConfigParam('sOePersonalizationExportPath', 'testExportPath');
        $this->assertEquals('testExportPath', $this->getViewConfig()->oePersonalizationGetExportPath());
    }

    public function testIsLoginActionSuccessWhenUserOnlyLogsIn()
    {
        $factoryMock = $this->prepareFactoryStubForUserLoginAction(false, true);

        UtilsObject::setClassInstance(Factory::class, $factoryMock);

        $this->assertTrue($this->getViewConfig()->oePersonalizationIsLoginAction());
    }

    public function testIsLoginActionSuccessWhenUserRegisters()
    {
        $factoryMock = $this->prepareFactoryStubForUserLoginAction(true, false);

        UtilsObject::setClassInstance(Factory::class, $factoryMock);

        $this->assertTrue($this->getViewConfig()->oePersonalizationIsLoginAction());
    }

    public function testIsNotLoginAction()
    {
        $factoryMock = $this->prepareFactoryStubForUserLoginAction(false, false);

        UtilsObject::setClassInstance(Factory::class, $factoryMock);

        $this->assertFalse($this->getViewConfig()->oePersonalizationIsLoginAction());
    }

    public function testIsLogoutAction()
    {
        $this->prepareActiveControllerToSetFunctionName('logout');

        $this->assertTrue($this->getViewConfig()->oePersonalizationIsLogoutAction());
    }

    public function testWhenIsNotLoginAction()
    {
        $this->prepareActiveControllerToSetFunctionName('home');

        $this->assertFalse($this->getViewConfig()->oePersonalizationIsLoginAction());
    }

    public function clientKeyProvider()
    {
        return [
            ['aaaaa', 'aaaaa'],
            ['aaaaa-1', 'aaaaa'],
            ['aaaaa-5', 'aaaaa'],
            ['aaaaa-5-9', 'aaaaa-5'],
            ['a-a-a/a-a-5-9', 'a-a-a/a-a-5'],
            ['00000cec-d98025a8-912b-46a4-a57d-7a691ba7a376-7', '00000cec-d98025a8-912b-46a4-a57d-7a691ba7a376'],
            ['00000cec-d98025a8-912b-46a4-a57d-7a691ba7a376-7a', '00000cec-d98025a8-912b-46a4-a57d-7a691ba7a376-7a'],
            ['00000cec-d98025a8-912b-46a4-a57d-7a691ba7a376-7121', '00000cec-d98025a8-912b-46a4-a57d-7a691ba7a376'],
            ['', ''],
            [null, null],
        ];
    }

    /**
     * @param string $accountId
     * @param string $clientKey
     *
     * @dataProvider clientKeyProvider
     */
    public function testClientKey($accountId, $clientKey)
    {
        Registry::getConfig()->setConfigParam('sOePersonalizationAccountId', $accountId);
        $this->assertEquals($clientKey, $this->getViewConfig()->oePersonalizationGetClientKey());
    }

    /**
     * @return object|\OxidEsales\Eshop\Core\ViewConfig|ViewConfig
     */
    protected function getViewConfig()
    {
        return oxNew(\OxidEsales\Eshop\Core\ViewConfig::class);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|Factory
     */
    private function prepareFactoryStubForUserLoginAction($isSuccessfulLogin, $isSuccessfulRegister)
    {
        $userActionIdentifier = $this->getMockBuilder(UserActionIdentifier::class)
            ->setMethods(['isSuccessfulLogin', 'isSuccessfulRegister'])
            ->disableOriginalConstructor()
            ->getMock();
        $userActionIdentifier->method('isSuccessfulLogin')->willReturn($isSuccessfulLogin);
        $userActionIdentifier->method('isSuccessfulRegister')->willReturn($isSuccessfulRegister);
        $factoryMock = $this->getMockBuilder(Factory::class)->setMethods(['makeUserActionIdentifier'])->getMock();
        $factoryMock->method('makeUserActionIdentifier')->willReturn($userActionIdentifier);

        return $factoryMock;
    }
}
