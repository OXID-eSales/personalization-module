<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EcondaModule\Component\Tracking;

use OxidEsales\Eshop\Core\Str;

/**
 * Class responsible for storing/providing customer's active page data.
 */
class ActivePageEntity implements ActivePageEntityInterface
{
    /**
     * tracker content
     *
     * @var string
     */
    private $content = null;

    /**
     * order process step information
     *
     * @var string
     */
    private $orderProcess = null;

    /**
     * site ID
     *
     * @var string
     */
    private $siteid = null;

    /**
     * Language ID
     *
     * @var string
     */
    private $langid = null;

    /**
     * Country ID
     *
     * @var string
     */
    private $countryid = null;

    /**
     * Page ID
     *
     * @var string
     */
    private $pageid = null;

    /**
     * Search Query string
     *
     * @var string
     */
    private $searchQuery = null;

    /**
     * Number of search hits
     *
     * @var int
     */
    private $searchNumberOfHits = null;

    /**
     * Register user hash
     *
     * @var string
     */
    private $registeredUserId = null;

    /**
     * Registration result
     *
     * @var string
     */
    private $registerResult = null;

    /**
     * Login user hash
     *
     * @var string
     */
    private $loginUserId = null;

    /**
     * Login result.
     *
     * @var string
     */
    private $loginResult = null;

    /**
     * Billing information
     *
     * @var array
     */
    private $billing = null;

    /**
     * @var array
     */
    private $productData = [];

    /**
     * @var array
     */
    private $boughtProducts = [];

    /**
     * @var array
     */
    private $productToBasket;

    /**
     * @var string
     */
    private $contactsMessage = '';

    /**
     * @var string
     */
    private $newsletterMessage = '';

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getOrderProcess()
    {
        return $this->orderProcess;
    }

    /**
     * @param string $orderProcess
     */
    public function setOrderProcess($orderProcess)
    {
        $this->orderProcess = $orderProcess;
    }

    /**
     * @return string
     */
    public function getSiteid()
    {
        return $this->siteid;
    }

    /**
     * @param string $siteid
     */
    public function setSiteid($siteid)
    {
        $this->siteid = $siteid;
    }

    /**
     * @return string
     */
    public function getLangid()
    {
        return $this->langid;
    }

    /**
     * @param string $langid
     */
    public function setLangid($langid)
    {
        $this->langid = $langid;
    }

    /**
     * @return string
     */
    public function getCountryid()
    {
        return $this->countryid;
    }

    /**
     * @param string $countryid
     */
    public function setCountryid($countryid)
    {
        $this->countryid = $countryid;
    }

    /**
     * @return string
     */
    public function getPageid()
    {
        return $this->pageid;
    }

    /**
     * @param string $pageid
     */
    public function setPageid($pageid)
    {
        $this->pageid = $pageid;
    }

    /**
     * @return string
     */
    public function getSearchQuery()
    {
        return $this->searchQuery;
    }

    /**
     * @param string $searchQuery
     */
    public function setSearchQuery($searchQuery)
    {
        // #4018: The emospro.search string is URL-encoded forwarded to econda instead of URL-escaped
        $this->searchQuery = DataFormater::format($searchQuery);
    }

    /**
     * @return int
     */
    public function getSearchNumberOfHits()
    {
        return $this->searchNumberOfHits;
    }

    /**
     * @param int $searchNumberOfHits
     */
    public function setSearchNumberOfHits($searchNumberOfHits)
    {
        $this->searchNumberOfHits = $searchNumberOfHits;
    }

    /**
     * @return string
     */
    public function getRegisteredUserId()
    {
        return $this->registeredUserId;
    }

    /**
     * @param string $registeredUserId
     */
    public function setRegisteredUserId($registeredUserId)
    {
        $this->registeredUserId = md5($registeredUserId);
    }

    /**
     * @return string
     */
    public function getRegisteredUserResult()
    {
        return $this->registerResult;
    }

    /**
     * @param string $registerResult
     */
    public function setRegisteredUserResult($registerResult)
    {
        $this->registerResult = $registerResult;
    }

    /**
     * @return string
     */
    public function getLoginUserId()
    {
        return $this->loginUserId;
    }

    /**
     * @param string $loginUserId
     */
    public function setLoginUserId($loginUserId)
    {
        $this->loginUserId = $loginUserId;
    }

    /**
     * @return string
     */
    public function getLoginResult()
    {
        return $this->loginResult;
    }

    /**
     * @param string $loginResult
     */
    public function setLoginResult($loginResult)
    {
        $this->loginResult = $loginResult;
    }

    /**
     * @return array
     */
    public function getBilling()
    {
        return $this->billing;
    }

    /**
     * @param string $billingId
     * @param string $customerNumber
     * @param int    $total
     * @param string $customerCountry
     * @param string $customerZipCode
     * @param string $customerCity
     */
    public function setBilling($billingId = "", $customerNumber = "", $total = 0, $customerCountry = "", $customerZipCode = "", $customerCity = "")
    {
        /******************* prepare data *************************************/
        /* md5 the customer id to fullfill requirements of german datenschutzgeesetz */
        $customerNumber = md5($customerNumber);

        $customerCountry = DataFormater::format($customerCountry);
        $customerZipCode = DataFormater::format($customerZipCode) ;
        $customerCity = DataFormater::format($customerCity);

        /* get a / separated location stzring for later drilldown */
        $ort = "";
        if ($customerCountry) {
            $ort .= "$customerCountry/";
        }

        if ($customerZipCode) {
            $ort .= Str::getStr()->substr($customerZipCode, 0, 1)."/".Str::getStr()->substr($customerZipCode, 0, 2)."/";
        }

        if ($customerCity) {
            $ort .= "$customerCity/";
        }

        if ($customerZipCode) {
            $ort.=$customerZipCode;
        }

        $this->billing = [$billingId, $customerNumber, $ort, $total];
    }

    /**
     * @return array
     */
    public function getProductData()
    {
        return $this->productData;
    }

    /**
     * @param array $productData
     */
    public function setProductData($productData)
    {
        $this->productData = $productData;
    }

    /**
     * @return array
     */
    public function getBoughtProducts()
    {
        return $this->boughtProducts;
    }

    /**
     * @param array $boughtProducts
     */
    public function setBoughtProducts($boughtProducts)
    {
        $this->boughtProducts = $boughtProducts;
    }

    /**
     * @return array
     */
    public function getProductToBasket()
    {
        return $this->productToBasket;
    }

    /**
     * @param array $productToBasket
     */
    public function setProductToBasket($productToBasket)
    {
        $this->productToBasket = $productToBasket;
    }

    /**
     * @return string
     */
    public function getContactsMessage()
    {
        return $this->contactsMessage;
    }

    /**
     * @param string $contactsMessage
     */
    public function setContactsMessage($contactsMessage)
    {
        $this->contactsMessage = $contactsMessage;
    }

    /**
     * @return string
     */
    public function getNewsletterMessage()
    {
        return $this->newsletterMessage;
    }

    /**
     * @param string $newsletterMessage
     */
    public function setNewsletterMessage($newsletterMessage)
    {
        $this->newsletterMessage = $newsletterMessage;
    }
}
