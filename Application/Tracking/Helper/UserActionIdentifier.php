<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Application\Tracking\Helper;

use OxidEsales\Eshop\Application\Model\User;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\PersonalizationModule\Application\Tracking\Page\PageIdentifiers;

/**
 * Class helps to identify user action.
 */
class UserActionIdentifier
{
    /**
     * @var User
     */
    private $user;

    /**
     * @var PageIdentifiers
     */
    private $pageIdentifiers;

    /**
     * @param User            $user
     * @param PageIdentifiers $pageIdentifiers
     */
    public function __construct($user, $pageIdentifiers)
    {
        $this->user = $user;
        $this->pageIdentifiers = $pageIdentifiers;
    }

    /**
     * @return bool
     */
    public function isSuccessfulLogin()
    {
        $isLoginAction = false;
        $this->user->loadActiveUser();
        if ('login_noredirect' == $this->pageIdentifiers->getCurrentFunctionName() && $this->user->isLoaded()) {
            $isLoginAction = true;
        }

        return $isLoginAction;
    }

    /**
     * @return bool
     */
    public function isSuccessfulRegister()
    {
        $isRegisterAction = false;
        $successCode = Registry::getRequest()->getRequestEscapedParameter('success');
        $this->user->loadActiveUser();
        if ($this->pageIdentifiers->getCurrentControllerName() === 'register'
            && $successCode > 0
            && $this->user->isLoaded()
        ) {
            $isRegisterAction = true;
        }

        return $isRegisterAction;
    }

    /**
     * @return bool
     */
    public function isSuccessfulLogout()
    {
        $isLogoutAction = false;
        if ('logout' == $this->pageIdentifiers->getCurrentFunctionName()) {
            $isLogoutAction = true;
        }

        return $isLogoutAction;
    }

    /**
     * @return bool
     */
    public function isInStartPage()
    {
        $isInStartPage = false;
        if ($this->pageIdentifiers->getCurrentControllerName() === 'start') {
            $isInStartPage = true;
        }

        return $isInStartPage;
    }
}
