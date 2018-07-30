<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Component\Tracking;

/**
 * Class responsible for storing/providing customer's active page data.
 */
interface ActivePageEntityInterface
{
    /**
     * @return string
     */
    public function getContent();

    /**
     * @param string $content
     */
    public function setContent($content);

    /**
     * @return string
     */
    public function getOrderProcess();

    /**
     * @param string $orderProcess
     */
    public function setOrderProcess($orderProcess);

    /**
     * @return string
     */
    public function getSiteid();

    /**
     * @param string $siteid
     */
    public function setSiteid($siteid);

    /**
     * @return string
     */
    public function getLangid();

    /**
     * @param string $langid
     */
    public function setLangid($langid);

    /**
     * @return string
     */
    public function getCountryid();

    /**
     * @param string $countryid
     */
    public function setCountryid($countryid);

    /**
     * @return string
     */
    public function getPageid();

    /**
     * @param string $pageid
     */
    public function setPageid($pageid);

    /**
     * @return string
     */
    public function getSearchQuery();

    /**
     * @param string $searchQuery
     */
    public function setSearchQuery($searchQuery);

    /**
     * @return int
     */
    public function getSearchNumberOfHits();

    /**
     * @param int $searchNumberOfHits
     */
    public function setSearchNumberOfHits($searchNumberOfHits);

    /**
     * @return string
     */
    public function getRegisteredUserId();

    /**
     * @param string $registeredUserId
     */
    public function setRegisteredUserId($registeredUserId);

    /**
     * @return string
     */
    public function getRegisteredUserResult();

    /**
     * @param int $registerResult
     */
    public function setRegisteredUserResult($registerResult);

    /**
     * @return string
     */
    public function getLoginUserId();

    /**
     * @param string $loginUserId
     */
    public function setLoginUserId($loginUserId);

    /**
     * @return string
     */
    public function getLoginResult();

    /**
     * @param int $loginResult
     */
    public function setLoginResult($loginResult);

    /**
     * @return array
     */
    public function getBilling();

    /**
     * @param string $billingId
     * @param string $customerNumber
     * @param int    $total
     * @param string $customerCountry
     * @param string $customerZipCode
     * @param string $customerCity
     */
    public function setBilling($billingId = "", $customerNumber = "", $total = 0, $customerCountry = "", $customerZipCode = "", $customerCity = "");

    /**
     * @return array
     */
    public function getProductData();

    /**
     * @param array $productData
     */
    public function setProductData($productData);

    /**
     * @return array
     */
    public function getBoughtProducts();

    /**
     * @param array $boughtProducts
     */
    public function setBoughtProducts($boughtProducts);

    /**
     * @return array
     */
    public function getProductToBasket();

    /**
     * @param array $productToBasket
     */
    public function setProductToBasket($productToBasket);

    /**
     * @return string
     */
    public function getContactsMessage();

    /**
     * @param string $contactsMessage
     */
    public function setContactsMessage($contactsMessage);

    /**
     * @return string
     */
    public function getNewsletterMessage();

    /**
     * @param string $newsletterMessage
     */
    public function setNewsletterMessage($newsletterMessage);
}
