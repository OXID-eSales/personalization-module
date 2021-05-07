<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Tests\Acceptance;

class TrackingTest extends BaseAcceptanceTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->startMinkSession('selenium');
        $this->enableTracking();
        $this->openShop();
    }

    public function testOpenStartPage()
    {
        $this->checkCommonElements();
        $this->checkIfElementInPage($this->prepareElementForContent('Start'));
        $this->checkIfElementInPage($this->prepareElementForPageId('1startpage/shop/start.tpl'));
    }

    public function testIfParentProductWithoutSku()
    {
        $this->open($this->getTestConfig()->getShopUrl() .'index.php?cl=details&cnid=oepersonalizationcategoryid&anid=1001');
        $this->checkIfElementInPage($this->prepareEvent('[{"type":"view","count":1,"pid":"1001","sku":null,"name":"Test product","group":"Test category\/Test product","price":10,"var1":"NULL","var2":"NULL","var3":"1001"}]'));
    }

    public function testIfVariantWithSku()
    {
        $this->open($this->getTestConfig()->getShopUrl() .'index.php?cl=details&cnid=oepersonalizationcategoryid&anid=1002');
        $this->checkIfElementInPage($this->prepareEvent('[{"type":"view","count":1,"pid":"1001","sku":"1002","name":"Test product","group":"Test category\/Test product","price":10,"var1":"NULL","var2":"NULL","var3":"1002"}]'));
    }

    public function testAddToBasket()
    {
        $this->addToBasket('1000', 1, 'Start');

        $this->checkCommonElements();
        $this->checkIfElementInPage($this->prepareElementForContent('Start'));
        $this->checkIfElementInPage($this->prepareElementForPageId('1startpage/shop/start.tpl1000'));
        $this->checkIfElementInPage($this->prepareEvent('[{"type":"c_add","count":"1","pid":"1000","sku":"1000","name":"Test product","group":"Test category\/Test product","price":10,"var1":"NULL","var2":"NULL","var3":"1000"}]'));

        $this->checkIfInPageThereIsNoDoubleEvent();
    }

    public function testCheckoutStep2()
    {
        $this->addToBasketProductAndGotToCheckoutPage();
        $this->clickAndWait("//button[contains(text(), '%CONTINUE_TO_NEXT_STEP%')]");

        $this->checkCommonElements();
        $this->checkIfElementInPage($this->prepareElementForContent('Shop\/Kaufprozess\/Kundendaten'));
        $this->checkIfElementInPage($this->prepareElementForPageId('1userpage/checkout/user.tpl'));
        $this->checkIfElementInPage($this->prepareElementForOrderProcess('2_Kundendaten'));
    }

    public function testCheckoutStep2WithoutRegistration()
    {
        $this->addToBasketProductAndGotToCheckoutPage();
        $this->clickAndWait("//button[contains(text(), '%CONTINUE_TO_NEXT_STEP%')]");
        $this->clickAndWait("//div[@id='optionNoRegistration']//button[contains(text(), '%NEXT%')]");

        $this->checkCommonElements();
        $this->checkIfElementInPage($this->prepareElementForContent('Shop\/Kaufprozess\/Kundendaten\/OhneReg'));
        $this->checkIfElementInPage($this->prepareElementForPageId('1userpage/checkout/user.tpl1'));
        $this->checkIfElementInPage($this->prepareElementForOrderProcess('2_Kundendaten\/OhneReg'));
    }

    public function testCheckoutStep2ExistingUser()
    {
        $this->addToBasketProductAndGotToCheckoutPage();
        $this->clickAndWait("//button[contains(text(), '%CONTINUE_TO_NEXT_STEP%')]");
        $this->clickAndWait("//div[@id='optionNoRegistration']//button[contains(text(), '%NEXT%')]");

        $this->checkCommonElements();
        $this->checkIfElementInPage($this->prepareElementForContent('Shop\/Kaufprozess\/Kundendaten\/OhneReg'));
        $this->checkIfElementInPage($this->prepareElementForPageId('1userpage/checkout/user.tpl1'));
        $this->checkIfElementInPage($this->prepareElementForOrderProcess('2_Kundendaten\/OhneReg'));
    }

    public function testCheckoutStep2NewAccount()
    {
        $this->addToBasketProductAndGotToCheckoutPage();
        $this->clickAndWait("//button[contains(text(), '%CONTINUE_TO_NEXT_STEP%')]");
        $this->clickAndWait("//div[@id='optionRegistration']//button[contains(text(), '%NEXT%')]");

        $this->checkCommonElements();
        $this->checkIfElementInPage($this->prepareElementForContent('Shop\/Kaufprozess\/Kundendaten\/NeuesKonto'));
        $this->checkIfElementInPage($this->prepareElementForPageId('1userpage/checkout/user.tpl3'));
        $this->checkIfElementInPage($this->prepareElementForOrderProcess('2_Kundendaten\/NeuesKonto'));
    }

    public function testCheckoutStep3()
    {
        $this->addToBasketProductAndGotToCheckoutPage();
        $this->clickAndWait("//button[contains(text(), '%CONTINUE_TO_NEXT_STEP%')]");
        $this->logInUser();
        $this->clickAndWait("//button[contains(text(), '%CONTINUE_TO_NEXT_STEP%')]");

        $this->checkCommonElements();
        $this->checkIfElementInPage($this->prepareElementForContent('Shop\/Kaufprozess\/Zahlungsoptionen'));
        $this->checkIfElementInPage($this->prepareElementForPageId('1paymentpage/checkout/payment.tpl'));
        $this->checkIfElementInPage($this->prepareElementForOrderProcess('3_Zahlungsoptionen'));
        $this->checkIfElementInPage($this->prepareElementForEmailShowEvent('testing_account@oxid-esales.local'));
    }

    public function testCheckoutStep4()
    {
        $this->addToBasketProductAndGotToCheckoutPage();
        $this->clickAndWait("//button[contains(text(), '%CONTINUE_TO_NEXT_STEP%')]");
        $this->logInUser();
        $this->clickAndWait("//button[contains(text(), '%CONTINUE_TO_NEXT_STEP%')]");
        $this->clickAndWait("//button[contains(text(), '%CONTINUE_TO_NEXT_STEP%')]");

        $this->checkCommonElements();
        $this->checkIfElementInPage($this->prepareElementForContent('Shop\/Kaufprozess\/Bestelluebersicht'));
        $this->checkIfElementInPage($this->prepareElementForPageId('1orderpage/checkout/order.tpl'));
        $this->checkIfElementInPage($this->prepareElementForOrderProcess('4_Bestelluebersicht'));
        $this->checkIfElementInPage($this->prepareElementForEmailShowEvent('testing_account@oxid-esales.local'));
    }

    public function testCheckThankYouPage()
    {
        $this->addToBasketProductAndGotToCheckoutPage();
        $this->clickAndWait("//button[contains(text(), '%CONTINUE_TO_NEXT_STEP%')]");
        $this->logInUser();
        $this->clickAndWait("//button[contains(text(), '%CONTINUE_TO_NEXT_STEP%')]");
        $this->clickAndWait("//button[contains(text(), '%CONTINUE_TO_NEXT_STEP%')]");
        $this->clickAndWait("//form[@id='orderConfirmAgbBottom']//button");

        $this->checkCommonElements();
        $this->checkIfElementInPage($this->prepareElementForContent('Shop\/Kaufprozess\/Bestaetigung'));
        $this->checkIfElementInPage($this->prepareElementForPageId('1thankyoupage/checkout/thankyou.tpl'));
        $this->checkIfElementInPage($this->prepareElementForOrderProcess('5_Bestaetigung'));
        $this->checkIfElementInPage($this->prepareEvent('[{"type":"buy","count":1,"pid":"1000","sku":"1000","name":"Test product","group":"Test category\/Test product","price":10,"var1":"NULL","var2":"NULL","var3":"1000"}]'));
        $this->checkIfElementInPage($this->prepareElementForEmailShowEvent('testing_account@oxid-esales.local'));
    }

    public function testCheckGuestUserThankYouPage()
    {
        $this->addToBasketProductAndGotToCheckoutPage();
        $this->clickAndWait("//button[contains(text(), '%CONTINUE_TO_NEXT_STEP%')]");
        $this->clickAndWait("//div[@id='optionNoRegistration']//button[contains(text(), '%NEXT%')]");
        $this->logInGuestUser();
        $this->clickAndWait("//button[contains(text(), '%CONTINUE_TO_NEXT_STEP%')]");
        $this->clickAndWait("//button[contains(text(), '%CONTINUE_TO_NEXT_STEP%')]");
        $this->clickAndWait("//form[@id='orderConfirmAgbBottom']//button");

        $this->checkCommonElements();
        $this->checkIfElementInPage($this->prepareElementForContent('Shop\/Kaufprozess\/Bestaetigung'));
        $this->checkIfElementInPage($this->prepareElementForPageId('1thankyoupage/checkout/thankyou.tpl'));
        $this->checkIfElementInPage($this->prepareElementForOrderProcess('5_Bestaetigung'));
        $this->checkIfElementInPage($this->prepareEvent('[{"type":"buy","count":1,"pid":"1000","sku":"1000","name":"Test product","group":"Test category\/Test product","price":10,"var1":"NULL","var2":"NULL","var3":"1000"}]'));
        $this->checkIfElementInPage($this->prepareElementForBilling('"2","8a9bd8030e4829e93191a781165af108","Germany\/5\/55\/Berlin\/55555",21.4'));
    }

    public function testCheckSearch()
    {
        $this->type("//input[@id='searchParam']", '1000');
        $this->clickAndWait("//form[@class='form search']//button");

        $this->checkCommonElements();
        $this->checkIfElementInPage($this->prepareElementForContent('Shop\/Suche'));
        $this->checkIfElementInPage($this->prepareElementForPageId('1searchpage/search/search.tpl'));
        $this->checkIfElementInPage($this->prepareElementForSearch('["1000","1"]'));
    }

    public function testGiftRegistry()
    {
        $this->logInUser();
        $this->clickAndWait("//ul/li/a[contains(text(), '%MY_GIFT_REGISTRY%')]");

        $this->checkCommonElements();
        $this->checkIfElementInPage($this->prepareElementForContent('Service\/Wunschzettel'));
        $this->checkIfElementInPage($this->prepareElementForPageId('1account_wishlistpage/account/wishlist.tpl'));
        $this->checkIfElementInPage($this->prepareElementForEmailShowEvent('testing_account@oxid-esales.local'));
    }

    public function testHelp()
    {
        $this->clickAndWait("//a[contains(text(), 'Help')]");

        $this->checkCommonElements();
        $this->checkIfElementInPage($this->prepareElementForContent('Content\/Help - Main'));
        $this->checkIfElementInPage($this->prepareElementForPageId('1contentpage/info/content.tpl'));
    }

    public function testNewsletter()
    {
        $this->type("//input[@id='footer_newsletter_oxusername']", 'test_user_email@oxid-esales.local');
        $this->clickAndWait("//button[contains(text(), '%SUBSCRIBE%')]");

        $this->checkCommonElements();
        $this->checkIfElementInPage($this->prepareElementForContent('Service\/Newsletter\/Form'));
        $this->checkIfElementInPage($this->prepareElementForPageId('1newsletterpage/info/newsletter.tpl'));

        $this->clickAndWait("//button[@id='newsLetterSubmit']");

        $this->checkCommonElements();
        $this->checkIfElementInPage($this->prepareElementForContent('Service\/Newsletter\/Success'));
        $this->checkIfElementInPage($this->prepareElementForPageId('1newsletterpage/info/newsletter.tpl'));
        $this->checkIfElementInPage($this->prepareElementTarget('["Newsletter","Newsletter registriert",1,"d"]'));
    }

    public function testLinks()
    {
        $this->clickAndWait("//a[contains(text(), 'Links')]");

        $this->checkCommonElements();
        $this->checkIfElementInPage($this->prepareElementForContent('Service\/Links'));
        $this->checkIfElementInPage($this->prepareElementForPageId('1linkspage/info/links.tpl'));
    }

    public function testInfoPages()
    {
        $this->open($this->getTestConfig()->getShopUrl() . 'en/About-Us/');

        $this->checkCommonElements();
        $this->checkIfElementInPage($this->prepareElementForContent('Info\/Impressum'));
        $this->checkIfElementInPage($this->prepareElementForPageId('1contentpage/info/content.tpl'));

        $this->open($this->getTestConfig()->getShopUrl() .'en/Terms-and-Conditions/');
        $this->checkCommonElements();
        $this->checkIfElementInPage($this->prepareElementForContent('Info\/AGB'));
        $this->checkIfElementInPage($this->prepareElementForPageId('1contentpage/info/content.tpl'));

        $this->open($this->getTestConfig()->getShopUrl() .'en/Privacy-Policy/');
        $this->checkCommonElements();
        $this->checkIfElementInPage($this->prepareElementForContent('Info\/Sicherheit'));
        $this->checkIfElementInPage($this->prepareElementForPageId('1contentpage/info/content.tpl'));

        $this->open($this->getTestConfig()->getShopUrl() .'en/Shipping-and-Charges/');
        $this->checkCommonElements();
        $this->checkIfElementInPage($this->prepareElementForContent('Info\/Versandinfo'));
        $this->checkIfElementInPage($this->prepareElementForPageId('1contentpage/info/content.tpl'));

        $this->open($this->getTestConfig()->getShopUrl() .'en/How-to-order/');
        $this->checkCommonElements();
        $this->checkIfElementInPage($this->prepareElementForContent('Info\/Bestellinfo'));
        $this->checkIfElementInPage($this->prepareElementForPageId('1contentpage/info/content.tpl'));
    }

    public function testContentPages()
    {
        $this->open($this->getTestConfig()->getShopUrl() .'en/Right-of-Withdrawal/');
        $this->checkCommonElements();
        $this->checkIfElementInPage($this->prepareElementForContent('Content\/Right of Withdrawal'));
        $this->checkIfElementInPage($this->prepareElementForPageId('1contentpage/info/content.tpl'));
    }

    public function testLogin()
    {
        $this->clickAndWait("//a[contains(text(), '%MY_ACCOUNT%')]");

        $this->checkCommonElements();
        $this->checkIfElementInPage($this->prepareElementForContent('Login\/Formular\/Login'));
        $this->checkIfElementInPage($this->prepareElementForPageId('1accountpage/account/login.tpl'));

        $this->logInUser();

        $this->checkCommonElements();
        $this->checkIfElementInPage($this->prepareElementForContent('Login\/Uebersicht'));
        $this->checkIfElementInPage($this->prepareElementForPageId('1accountpage/account/dashboard.tpl'));
        $this->checkIfElementInPage($this->prepareElementForLoginEvent('"'.md5('oepersonalizationtestuser').'",0'));
        $this->checkIfElementInPage($this->prepareElementForEmailShowEvent('testing_account@oxid-esales.local'));

        $this->open($this->getTestConfig()->getShopUrl() .'/index.php?cl=account&fnc=logout&redirect=1');

        $this->checkCommonElements();
        $this->checkIfElementInPage($this->prepareElementForContent('Login\/Formular\/Logout'));
        $this->checkIfElementInPage($this->prepareElementForPageId('1accountpage/account/login.tpl'));
        $this->checkIfElementNotInPage($this->prepareElementForEmailShowEvent('testing_account@oxid-esales.local'));
    }

    public function testUserAccountPagesBrowsing()
    {
        $this->logInUser();
        $this->open($this->getTestConfig()->getShopUrl() .'en/my-address/');

        $this->checkCommonElements();
        $this->checkIfElementInPage($this->prepareElementForContent('Login\/Kundendaten'));
        $this->checkIfElementInPage($this->prepareElementForPageId('1account_userpage/account/user.tpl'));

        $this->open($this->getTestConfig()->getShopUrl() .'en/order-history/');

        $this->checkCommonElements();
        $this->checkIfElementInPage($this->prepareElementForContent('Login\/Bestellungen'));
        $this->checkIfElementInPage($this->prepareElementForPageId('1account_orderpage/account/order.tpl'));

        $this->open($this->getTestConfig()->getShopUrl() .'en/my-wish-list/');

        $this->checkCommonElements();
        $this->checkIfElementInPage($this->prepareElementForContent('Login\/Merkzettel'));
        $this->checkIfElementInPage($this->prepareElementForPageId('1account_noticelistpage/account/noticelist.tpl'));

        $this->open($this->getTestConfig()->getShopUrl() .'/index.php?cl=account_newsletter');

        $this->checkCommonElements();
        $this->checkIfElementInPage($this->prepareElementForContent('Login\/Newsletter'));
        $this->checkIfElementInPage($this->prepareElementForPageId('1account_newsletterpage/account/newsletter.tpl'));

        $this->open($this->getTestConfig()->getShopUrl() .'en/my-gift-registry/');

        $this->checkCommonElements();
        $this->checkIfElementInPage($this->prepareElementForContent('Service\/Wunschzettel'));
        $this->checkIfElementInPage($this->prepareElementForPageId('1account_wishlistpage/account/wishlist.tpl'));
        $this->checkIfElementInPage($this->prepareElementForEmailShowEvent('testing_account@oxid-esales.local'));
    }

    public function testUserForgotPassword()
    {
        $this->open($this->getTestConfig()->getShopUrl() .'en/forgot-password/');

        $this->checkCommonElements();
        $this->checkIfElementInPage($this->prepareElementForContent('Content\/page\/account\/forgotpwd'));
        $this->checkIfElementInPage($this->prepareElementForPageId('1forgotpwdpage/account/forgotpwd.tpl'));
    }

    public function testUserRegister()
    {
        $this->open($this->getTestConfig()->getShopUrl() .'en/open-account/');

        $this->checkCommonElements();
        $this->checkIfElementInPage($this->prepareElementForContent('Service\/Register'));
        $this->checkIfElementInPage($this->prepareElementForPageId('1registerpage/account/register.tpl'));

        $this->type("//input[@id='userLoginName']", 'testing_account2@oxid-esales.local');
        $this->type("//input[@id='userPassword']", 'test_password');
        $this->type("//input[@id='userPasswordConfirm']", 'test_password');
        $this->type("//input[@name='invadr[oxuser__oxfname]']", 'test');
        $this->type("//input[@name='invadr[oxuser__oxlname]']", 'test');
        $this->type("//input[@name='invadr[oxuser__oxstreet]']", 'test');
        $this->type("//input[@name='invadr[oxuser__oxstreetnr]']", '11');
        $this->type("//input[@name='invadr[oxuser__oxzip]']", 'test');
        $this->type("//input[@name='invadr[oxuser__oxcity]']", 'test');
        $this->type("//select[@name='invadr[oxuser__oxcountryid]']", 'a7c40f631fc920687.20179984');
        $this->clickAndWait("//button[@id='accUserSaveTop']");

        $this->checkIfElementInPage($this->prepareElementForLoginEvent(''));
        $this->checkIfElementInPage($this->prepareElementForRegisterEvent(''));
        $this->checkIfElementInPage($this->prepareElementForEmailShowEvent('testing_account2@oxid-esales.local'));
    }

    public function testContactsSuccess()
    {
        $this->open($this->getTestConfig()->getShopUrl() .'en/contact/');

        $this->checkCommonElements();
        $this->checkIfElementInPage($this->prepareElementForContent('Service\/Kontakt\/Form'));
        $this->checkIfElementInPage($this->prepareElementForPageId('1contactpage/info/contact.tpl'));

        $this->type("//input[@name='editval[oxuser__oxfname]']", 'test');
        $this->type("//input[@name='editval[oxuser__oxlname]']", 'test');
        $this->type("//input[@name='editval[oxuser__oxusername]']", 'testing_account@oxid-esales.local');
        $this->type("//input[@name='c_subject']", 'test subject');
        $this->clickAndWait("//form[@class='form-horizontal']//button[@type='submit']");

        $this->checkCommonElements();
        $this->checkIfElementInPage($this->prepareElementForContent('Service\/Kontakt\/Success'));
        $this->checkIfElementInPage($this->prepareElementForPageId('1contactpage/info/contact.tpl'));
        $this->checkIfElementInPage($this->prepareElementTarget('["Kontakt","Kontaktformular gesendet",1,"d"]'));
    }

    public function testContactsFailure()
    {
        $this->open($this->getTestConfig()->getShopUrl() .'en/contact/');

        $this->type("//input[@name='editval[oxuser__oxfname]']", 'test');
        $this->type("//input[@name='editval[oxuser__oxlname]']", 'test');
        $this->type("//input[@name='editval[oxuser__oxusername]']", 'test');
        $this->clickAndWait("//form[@class='form-horizontal']//button[@type='submit']");

        $this->checkCommonElements();
        $this->checkIfElementInPage($this->prepareElementForContent('Service\/Kontakt\/Form'));
        $this->checkIfElementInPage($this->prepareElementForPageId('1contactpage/info/contact.tpl'));
    }

    protected function prepareElementForPageId($elementsForId)
    {
        $id = md5($elementsForId);
        return "\"pageId\":\"$id\"";
    }

    protected function prepareElementForContent($value)
    {
        return "\"content\":\"$value\"";
    }

    protected function prepareEvent($event)
    {
        return "\"ec_Event\":$event";
    }

    protected function prepareElementForOrderProcess($processId)
    {
        return "\"orderProcess\":\"$processId\"";
    }

    protected function prepareElementForBilling($value)
    {
        return "\"billing\":[$value]";
    }

    protected function prepareElementForSearch($value)
    {
        return "\"search\":$value";
    }

    protected function prepareElementForSiteId($id)
    {
        return "\"siteid\":\"$id\"";
    }

    protected function prepareElementForLanguageId($id)
    {
        return "\"langid\":\"$id\"";
    }

    protected function prepareElementTarget($value)
    {
        return "\"Target\":$value";
    }

    protected function prepareElementForLoginEvent($value)
    {
        return "\"login\":[$value";
    }

    protected function prepareElementForRegisterEvent($value)
    {
        return "\"register\":[$value";
    }

    protected function prepareElementForEmailShowEvent($value)
    {
        $value = md5($value);
        return "\"hashedvalue\":[[\"$value\"]]";
    }

    protected function checkIfElementInPage($element)
    {
        $minkSession = $this->getMinkSession();
        $page = $minkSession->getPage();
        $html = $page->getHtml();
        $this->assertTrue(strpos($html, $element) !== false, "Element $element was not found in page.");
    }

    protected function checkIfElementNotInPage($element)
    {
        $minkSession = $this->getMinkSession();
        $page = $minkSession->getPage();
        $html = $page->getHtml();
        $this->assertFalse(strpos($html, $element) !== false, "Element $element was not found in page.");
    }

    protected function addToBasketProductAndGotToCheckoutPage()
    {
        $this->addToBasket('1000');
    }

    protected function logInUser()
    {
        $this->type("//input[@name='lgn_usr']", 'testing_account@oxid-esales.local');
        $this->type("//input[@name='lgn_pwd']", 'useruser');
        $this->clickAndWait("//form[@name='login']//button[contains(text(), '%LOGIN%')]");
    }

    protected function logInGuestUser()
    {
        $this->type("//input[@id='userLoginName']", "test1@oxid-esales.dev");
        $this->type("invadr[oxuser__oxfname]", "tname");
        $this->type("invadr[oxuser__oxlname]", "fname");
        $this->type("invadr[oxuser__oxstreet]", "test street");
        $this->type("invadr[oxuser__oxstreetnr]", "10");
        $this->type("invadr[oxuser__oxzip]", "55555");
        $this->type("invadr[oxuser__oxcity]", "Berlin");
        $this->select("invadr[oxuser__oxcountryid]", "Germany");
    }
    
    protected function getSiteId()
    {
        return str_ireplace('www.', '', parse_url($this->getTestConfig()->getShopUrl(), PHP_URL_HOST));
    }

    protected function checkCommonElements()
    {
        $this->checkIfElementInPage($this->prepareElementForSiteId($this->getSiteId()));
        $this->checkIfElementInPage($this->prepareElementForLanguageId(1));
    }

    /**
     * Checks if there is c_add event and not view event.
     */
    private function checkIfInPageThereIsNoDoubleEvent()
    {
        $this->openArticle('1000');
//        $this->waitForElement("//button[@id='toBasket']");
//        $this->open($this->getTestConfig()->getShopUrl() . 'en/Test-category/Test-product.html');
        $this->clickAndWait("//button[@id='toBasket']");
        $this->checkIfElementNotInPage('{"type":"view"');
        $this->checkIfElementInPage($this->prepareEvent('[{"type":"c_add","count":"1","pid":"1000","sku":"1000","name":"Test product","group":"Test category\/Test product","price":10,"var1":"NULL","var2":"NULL","var3":"1000"}]'));
    }
}
