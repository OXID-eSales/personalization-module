[{$smarty.block.parent}]

[{capture append="oxidBlock_pageHead"}]
    [{if $oViewConf->oeEcondaEnableWidgets()}]
        [{block name="oeeconda_add_js"}]
            <script type="text/javascript" src="[{$oViewConf->getModuleUrl("oeeconda", "out/js/econda-recommendations.js")}]"></script>
            [{if $oViewConf->oeEcondaIsLoginAction()}]
                <script type="text/javascript">
                    econda.data.visitor.login({
                        ids: {userId: '[{$oViewConf->oeEcondaGetLoggedInUserHashedId()}]', emailHash: '[{$oViewConf->oeEcondaGetLoggedInUserHashedEmail()}]'}
                    });
                </script>
            [{/if}]
            [{if $oViewConf->oeEcondaIsLogoutAction()}]
                <script type="text/javascript">
                    econda.data.visitor.logout();
                </script>
            [{/if}]
        [{/block}]
    [{/if}]
[{/capture}]
