<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Component\Export;

/**
 * Generates column names for multilingual fields.
 */
class ColumnNameVariationsGenerator
{
    /**
     * @var int
     */
    private $languagesAmount;

    /**
     * @param int $languagesAmount
     */
    public function __construct(int $languagesAmount)
    {
        $this->languagesAmount = $languagesAmount;
    }

    /**
     * @param string $name
     *
     * @return array
     */
    public function generateNames(string $name): array
    {
        $values = [$name];
        $languagesAmount = $this->languagesAmount;
        if ($languagesAmount > 1) {
            $index = 1;
            while ($index < $languagesAmount) {
                $values[] = $name . '_var' . $index;
                $index++;
            }
        }

        return $values;
    }
}
