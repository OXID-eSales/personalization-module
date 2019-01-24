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
                <script type="text/javascript">
                    [{assign var="moreInfo" value='MORE_INFO'|oxmultilangassign|oxaddslashes}]
                    var lang_MORE_INFO = '[{$moreInfo}]';
                    var widget = new econda.recengine.Widget({
                        element: '#oePersonalizationRelatedProductsCrossSelling .inner',
                        renderer: {
                            type: 'template',
                            uri: '[{$oViewConf->oePersonalizationGetDetailsPageTemplateUrl()}]'
                        },
                        accountId: '[{$oViewConf->oePersonalizationGetAccountId()}]',
                        id: '[{$oViewConf->oePersonalizationGetDetailsPageWidgetId()}]',
                        context: {
                            products: new Array({
                                id: '[{$oView->oePersonalizationGetProductNumber()}]'
                            })
                        },
                        chunkSize: 4
                    });
                    widget.render();
                </script>
            </div>
        [{/capture}]
    [{/block}]
[{/if}]
