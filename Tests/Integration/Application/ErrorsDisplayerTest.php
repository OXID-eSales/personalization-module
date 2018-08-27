<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Tests\Integration\Application;

use OxidEsales\Eshop\Core\DisplayError;
use OxidEsales\PersonalizationModule\Application\Controller\Admin\ErrorDisplayer;

class ErrorsDisplayerTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    public function testIfErrorIsSet()
    {
        $displayer = oxNew(ErrorDisplayer::class);
        $displayer->addErrorToDisplay('Test error');
        $errors = \OxidEsales\Eshop\Core\Registry::getSession()->getVariable('Errors');

        /** @var DisplayError $error */
        $error = unserialize($errors['default'][0]);

        $this->assertSame('Test error', $error->getOxMessage());
    }
}
