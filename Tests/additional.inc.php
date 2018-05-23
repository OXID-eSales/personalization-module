<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

$serviceCaller = new \OxidEsales\TestingLibrary\ServiceCaller();
$testConfig = new \OxidEsales\TestingLibrary\TestConfig();

$serviceCaller->setParameter( 'importSql', '@' . __DIR__ . '/fixtures/testdata_' . strtolower( $testConfig->getShopEdition() ) . '.sql' );
$serviceCaller->callService( 'ShopPreparation', 1 );
