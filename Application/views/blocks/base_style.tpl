[{$smarty.block.parent}]

[{block name="oepersonalization_add_js"}]
    [{capture append="oxidBlock_pageHead"}]
        <script type="text/javascript" src="[{$oViewConf->getModuleUrl('oepersonalization', 'out/js/econda-recommendations.js')}]"></script>
        <script type="text/javascript">
            [{if $oViewConf->oePersonalizationShowTrackingNote() == 'opt_in'}]
            if (econda.privacyprotection.getPermissionsFromLocalStorage().profile.state === 'UNKNOWN') {
                var emosProps = {};
                econda.privacyprotection.applyAndStoreNewPrivacySettings(
                    emosProps,
                    {
                        "permissions:profile": {
                            state: "DENY"
                        }
                    }
                );
            }
            [{/if}]
            [{if $oViewConf->oePersonalizationShowTrackingNote() == 'opt_out'}]
            if (econda.privacyprotection.getPermissionsFromLocalStorage().profile.state === 'UNKNOWN') {
                var emosProps = {};
                econda.privacyprotection.applyAndStoreNewPrivacySettings(
                    emosProps,
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
    [{oxscript include=$oViewConf->getModuleUrl('oepersonalization','out/js/oepersonalization.js')}]
    [{if $oViewConf->oePersonalizationEnableWidgets()}]
        [{if $oViewConf->oePersonalizationIsLoginAction()}]
        <script type="text/javascript">
            econda.data.visitor.login({
                ids: {userId: '[{$oViewConf->oePersonalizationGetLoggedInUserHashedId()}]', emailHash: '[{$oViewConf->oePersonalizationGetLoggedInUserHashedEmail()}]'}
            });
        </script>
        [{/if}]
        [{if $oViewConf->oePersonalizationIsLogoutAction()}]
        <script type="text/javascript">
            econda.data.visitor.logout();
        </script>
        [{/if}]
    [{/if}]
[{/block}]
