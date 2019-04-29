[{$smarty.block.parent}]

[{if $oViewConf->oePersonalizationEnableWidgets() && $oViewConf->oePersonalizationGetDetailsPageWidgetId()}]
    [{block name="oepersonalization_details_relatedproducts_crossselling"}]
        [{capture append="oxidBlock_productbar"}]
            <div class="boxwrapper">
                <div class="page-header">
                    <h2>[{oxmultilang ident="HAVE_YOU_SEEN"}]</h2>
                    <small class="subhead">[{oxmultilang ident="WIDGET_PRODUCT_RELATED_PRODUCTS_CROSSSELING_SUBHEADER"}]</small>
                </div>
                <div id="oePersonalizationRelatedProductsCrossSelling">
                    <div class="inner list-container">
                        [{include file=$oViewConf->getModulePath('oepersonalization','Application/views/blocks/widgets/includes/preloader.tpl')}]
                    </div>
                </div>
                [{assign var="moreInfo" value='MORE_INFO'|oxmultilangassign|oxaddslashes}]
                [{assign var="widgetScript" value='
                    var lang_MORE_INFO = "'|cat:$moreInfo|cat:'";
                    (function () {
                        var widget = new econda.recengine.Widget({
                            element: "#oePersonalizationRelatedProductsCrossSelling .inner",
                            renderer: {
                                type: "template",
                                uri: "'|cat:$oViewConf->oePersonalizationGetDetailsPageTemplateUrl()|cat:'"
                            },
                            accountId: "'|cat:$oViewConf->oePersonalizationGetAccountId()|cat:'",
                            id: "'|cat:$oViewConf->oePersonalizationGetDetailsPageWidgetId()|cat:'",
                            context: {
                                products: new Array({
                                    id:  "'|cat:$oView->oePersonalizationGetProductNumber()|cat:'"
                                })
                            },
                            chunkSize: 4
                        });
                        widget.render();
                    })();
                '}]
                [{oxscript add=$widgetScript}]
            </div>
        [{/capture}]
    [{/block}]
[{/if}]
