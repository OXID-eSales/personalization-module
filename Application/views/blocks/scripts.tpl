[{$smarty.block.parent}]

[{block name="oepersonalization_add_js_in_head"}]
    [{if $oViewConf->oePersonalizationIsTrackingEnabled() === true}]
        <script type="text/javascript">
            window.emos3 = {
                stored : [],
                send : function(p){this.stored.push(p);}
            };
        </script>
        <script type="text/javascript" src="[{$oViewConf->oePersonalizationGetTrackingJsFileUrl()}]"></script>
    [{/if}]
    <script type="text/javascript" src="[{$oViewConf->getModuleUrl('oepersonalization', 'out/js/econda-recommendations.js')}]"></script>
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
        [{if $oViewConf->oePersonalizationIsLoginAction() || $oViewConf->isStartPage()}]
        <script type="text/javascript">
            econda.privacyprotection.updatePrivacySettingsFromBackend('[{$oViewConf->oePersonalizationGetClientKey()}]', 'privacy_protection');
        </script>
        [{/if}]
    [{/if}]
[{/block}]

[{block name="oepersonalization_add_js_in_footer"}]
    [{capture append="oxidBlock_pageScript"}]
            [{if $oViewConf->oePersonalizationIsTagManagerActive()}]
                <script type="text/javascript">
                    if (typeof econdaData === 'undefined') {
                        econdaData = {};
                    }
                </script>
                <div id="econdaTMC"></div>
                <script type="text/javascript" id="econdaTM">econdaTMD=econdaData;econdatm={stored:[],results:null,
                        event:function(p){this.stored.push(p);},result:function(r){this.results=r;}};
                    (function(d,s){var f=d.getElementById(s),j=d.createElement('script');
                        j.async=true;j.src='[{$oViewConf->oePersonalizationGetTagManagerJsFileUrl()}]';f.parentNode.insertBefore(j,f);})
                    (document,'econdaTM');</script>
            [{/if}]
            [{if $oViewConf->showPrivacyProtectionBanner() == true}]
                <script type="text/javascript" defer="defer" src="https://d35ojb8dweouoy.cloudfront.net/loader/loader.js"
                        client-key="[{$oViewConf->oePersonalizationGetClientKey()}]"
                        container-id="[{$oViewConf->oePersonalizationGetContainerId()}]"></script>
            [{/if}]
    [{/capture}]
[{/block}]
