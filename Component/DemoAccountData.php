<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EcondaModule\Component;

/**
 * Class contains Econda demo shop information.
 */
class DemoAccountData
{
    public static function getAccountId()
    {
        return '00000cec-d98025a8-912b-46a4-a57d-7a691ba7a376-7';
    }

    public static function getProductId()
    {
        return '00000001248553';
    }

    public static function getListPageWidgetId()
    {
        return '43';
    }

    public static function getDetailsPageWidgetId()
    {
        return '46';
    }

    public static function getThankYouPageWidgetId()
    {
        return '39';
    }

    public static function getStartPageBestOffersWidgetId()
    {
        return '42'; // Widget 40 does not work.
    }

    public static function getStartPageBestSellerWidgetId()
    {
        return '39';
    }

    public static function getCategoryId()
    {
        return 'herren';
    }
}
