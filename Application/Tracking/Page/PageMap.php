<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EcondaModule\Application\Tracking\Page;

/**
 * Class responsible for having page maps.
 */
class PageMap
{
    /**
     * Tracking code generator pages content.
     *
     * @var array
     */
    private $pagesContent = [
        'start' => 'Start',
        'basket' => 'Shop/Kaufprozess/Warenkorb',
        'user' => 'Shop/Kaufprozess/Kundendaten',
        'user_1' => 'Shop/Kaufprozess/Kundendaten/OhneReg',
        'user_2' => 'Shop/Kaufprozess/Kundendaten/BereitsKunde', // In OXID eShop 6.1 does not exists.
        'user_3' => 'Shop/Kaufprozess/Kundendaten/NeuesKonto',
        'payment' => 'Shop/Kaufprozess/Zahlungsoptionen',
        'order' => 'Shop/Kaufprozess/Bestelluebersicht',
        'thankyou' => 'Shop/Kaufprozess/Bestaetigung',
        'search' => 'Shop/Suche',
        'account_wishlist' => 'Service/Wunschzettel',
        'contact_success' => 'Service/Kontakt/Success',
        'contact_failure' => 'Service/Kontakt/Form',
        'help' => 'Service/Hilfe',
        'newsletter_success' => 'Service/Newsletter/Success',
        'newsletter_failure' => 'Service/Newsletter/Form',
        'links' => 'Service/Links',
        'info_impressum.tpl' => 'Info/Impressum',
        'info_agb.tpl' => 'Info/AGB',
        'info_order_info.tpl' => 'Info/Bestellinfo',
        'info_delivery_info.tpl' => 'Info/Versandinfo',
        'info_security_info.tpl' => 'Info/Sicherheit',
        'account_login' => 'Login/Uebersicht',
        'account_logout' => 'Login/Formular/Logout',
        'account_needlogin' => 'Login/Formular/Login',
        'account_user' => 'Login/Kundendaten',
        'account_order' => 'Login/Bestellungen',
        'account_noticelist' => 'Login/Merkzettel',
        'account_newsletter' => 'Login/Newsletter',
        'account_whishlist' => 'Login/Wunschzettel',
        'forgotpassword' => 'Login/PW vergessen',
        'content_oximpressum' => 'Info/Impressum',
        'content_oxagb' => 'Info/AGB',
        'content_oxorderinfo' => 'Info/Bestellinfo',
        'content_oxdeliveryinfo' => 'Info/Versandinfo',
        'content_oxsecurityinfo' => 'Info/Sicherheit',
        'register' => 'Service/Register',
    ];

    /**
     * Tracking code generator order step names.
     *
     * @var array
     */
    private $orderStepNames = [
        'basket' => '1_Warenkorb',
        'order_process' => '2_Kundendaten',
        'user' => '2_Kundendaten',
        'user_1' => '2_Kundendaten/OhneReg',
        'user_2' => '2_Kundendaten/BereitsKunde',
        'user_3' => '2_Kundendaten/NeuesKonto',
        'payment' => '3_Zahlungsoptionen',
        'order' => '4_Bestelluebersicht',
        'thankyou' => '5_Bestaetigung',
    ];

    /**
     * @return array
     */
    public function getPagesContent()
    {
        return $this->pagesContent;
    }

    /**
     * @return array
     */
    public function getOrderStepNames()
    {
        return $this->orderStepNames;
    }
}
