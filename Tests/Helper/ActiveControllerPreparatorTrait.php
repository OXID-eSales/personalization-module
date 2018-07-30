<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Tests\Helper;

use OxidEsales\Eshop\Application\Controller\FrontendController;
use OxidEsales\Eshop\Core\Config;
use OxidEsales\Eshop\Core\Registry;

trait ActiveControllerPreparatorTrait
{
    protected function prepareActiveControllerToSetFunctionName($functionName)
    {
        $activeController = $this->getMockBuilder(FrontendController::class)
            ->setMethods(['getFncName'])
            ->getMock();
        $activeController->method('getFncName')->willReturn($functionName);

        $this->injectActiveControllerToConfig($activeController);
    }

    protected function prepareActiveControllerName($activeControllerName)
    {
        $activeController = $this->getMockBuilder(FrontendController::class)
            ->setMethods(['getClassKey'])
            ->getMock();
        $activeController->method('getClassKey')->willReturn($activeControllerName);

        $this->injectActiveControllerToConfig($activeController);
    }

    /**
     * @param $activeController
     */
    private function injectActiveControllerToConfig($activeController)
    {
        $config = new Config();
        $config->setActiveView($activeController);

        Registry::set(Config::class, $config);
    }
}
