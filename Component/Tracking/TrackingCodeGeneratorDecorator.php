<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Component\Tracking;

use OxidEsales\EcondaTrackingComponent\TrackingCodeGenerator\TrackingCodeGeneratorInterface;

/**
 * Class adds additional scripts.
 */
class TrackingCodeGeneratorDecorator implements TrackingCodeGeneratorInterface
{
    /**
     * @var TrackingCodeGeneratorInterface
     */
    private $trackingCodeGenerator;

    /**
     * @param TrackingCodeGeneratorInterface $trackingCodeGenerator
     */
    public function __construct(TrackingCodeGeneratorInterface $trackingCodeGenerator)
    {
        $this->trackingCodeGenerator = $trackingCodeGenerator;
    }

    /**
     * @return string
     */
    public function generateCode(): string
    {
        $jsCode = '';
        $jsCode .= '<script type="text/javascript">';
        $jsCode .= 'econda.privacyprotection.setEmos3PrivacySettings();';
        $jsCode .= '</script>';
        $jsCode .= $this->trackingCodeGenerator->generateCode();

        return $jsCode;
    }
}
