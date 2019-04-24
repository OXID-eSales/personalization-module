[{$smarty.block.parent}]

[{if $oViewConf->oePersonalizationEnableWidgets() && $oViewConf->oePersonalizationGetThankYouPageWidgetId()}]
    [{block name="oepersonalization_checkout_thankyou_info"}]
        <div class="boxwrapper">
            <div class="page-header">
                <h2>[{oxmultilang ident="OEPERSONALIZATION_MIGHT_INTEREST"}]</h2>
            </div>
            <div id="oePersonalizationThankYouInfo">
                <div class="inner list-container">
                    [{include file=$oViewConf->getModulePath('oepersonalization','Application/views/blocks/widgets/includes/preloader.tpl')}]
                </div>
            </div>
            [{assign var="moreInfo" value='MORE_INFO'|oxmultilangassign|oxaddslashes}]
            [{assign var="widgetScript" value='
                var lang_MORE_INFO = "'|cat:$moreInfo|cat:'";
                (function () {
                    var widget = new econda.recengine.Widget({
                        element: "#oePersonalizationThankYouInfo .inner",
                        renderer: {
                            type: "template",
                            uri: "'|cat:$oViewConf->oePersonalizationGetThankYouPageTemplateUrl()|cat:'"
                        },
                        accountId: "'|cat:$oViewConf->oePersonalizationGetAccountId()|cat:'",
                        id: "'|cat:$oViewConf->oePersonalizationGetThankYouPageWidgetId()|cat:'",
                        chunkSize: 4,
                        autoContext: true
                    });
                    widget.render();
                })();
            '}]
            [{oxscript add=$widgetScript}]
        </div>
    [{/block}]
[{/if}]
