[{$smarty.block.parent}]

[{block name="oeeconda_add_js"}]
    [{capture append="oxidBlock_pageHead"}]
        <script type="text/javascript" src="[{$oViewConf->getModuleUrl('oeeconda', 'out/js/econda-recommendations.js')}]"></script>
        <script type="text/javascript">
            [{if $oViewConf->oeEcondaShowTrackingNote() == 'opt_in'}]
            if (econda.privacyprotection.getPermissionsFromLocalStorage().profile.state === 'UNKNOWN') {
                econda.privacyprotection.applyAndStoreNewPrivacySettings(
                    { },
                    {
                        "permissions:profile": {
                            state: "DENY"
                        }
                    }
                );
            }
            [{/if}]
            [{if $oViewConf->oeEcondaShowTrackingNote() == 'opt_out'}]
            if (econda.privacyprotection.getPermissionsFromLocalStorage().profile.state === 'UNKNOWN') {
                econda.privacyprotection.applyAndStoreNewPrivacySettings(
                    { },
                    {
                        "permissions:profile": {
                            state: "ALLOW"
                        }
                    }
                );
            }
            [{/if}]
        </script>
    [{/capture}]
    [{oxscript include=$oViewConf->getModuleUrl('oeeconda','out/js/oeeconda.js')}]
    [{if $oViewConf->oeEcondaEnableWidgets()}]
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
    [{/if}]
[{/block}]
