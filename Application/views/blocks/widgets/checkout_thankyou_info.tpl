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
            <script type="text/javascript">
                var lang_MORE_INFO = '[{oxmultilang ident="MORE_INFO"}]';
                var widget = new econda.recengine.Widget({
                    element: '#oePersonalizationThankYouInfo .inner',
                    renderer: {
                        type: 'template',
                        uri: '[{$oViewConf->oePersonalizationGetThankYouPageTemplateUrl()}]'
                    },
                    accountId: '[{$oViewConf->oePersonalizationGetAccountId()}]',
                    id: '[{$oViewConf->oePersonalizationGetThankYouPageWidgetId()}]',
                    chunkSize: 4,
                    autoContext: true
                });
                widget.render();
            </script>
        </div>
    [{/block}]
[{/if}]
