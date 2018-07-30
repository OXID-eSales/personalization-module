<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

/**
 * Smarty plugin
 * -------------------------------------------------------------
 * File: insert.oxid_tracker.php
 * Type: string, html
 * Name: oxid_tracker
 * Purpose: Output etracker code or Econda Code
 * add [{insert name="oxid_tracker" title="..."}] after Body Tag in Templates
 * -------------------------------------------------------------
 *
 * @param array  $params  params
 * @param Smarty &$smarty clever simulation of a method
 *
 * @return string
 */
function smarty_insert_oxid_tracker($params, &$smarty)
{
    $config = \OxidEsales\Eshop\Core\Registry::getConfig();
    if ($config->getConfigParam('blOePersonalizationTracking')) {
        $factory = oxNew(\OxidEsales\PersonalizationModule\Application\Factory::class);

        $entity = $factory->getActivePageEntityPreparator()->prepareEntity($params, $smarty);
        $trackingCodeGenerator = $factory->makeTrackingCodeGenerator($entity, $params, $smarty);
        $output = $trackingCodeGenerator->generateCode();

        // returning JS code to output
        if (strlen(trim($output))) {
            return "<div style=\"display:none;\">{$output}</div>";
        }
    }
}
