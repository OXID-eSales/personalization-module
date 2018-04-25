[{$smarty.block.parent}]

[{block name="oeeconda_details_relatedproducts_crossselling"}]
    [{capture append="oxidBlock_productbar"}]
        <div class="boxwrapper">
            <div class="page-header">
                <h2>[{oxmultilang ident="HAVE_YOU_SEEN"}]</h2>
                <small class="subhead">[{oxmultilang ident="WIDGET_PRODUCT_RELATED_PRODUCTS_CROSSSELING_SUBHEADER"}]</small>
            </div>
            <div id="oeEcondaRelatedProductsCrossSelling">
                <div class="inner list-container">
                    [{include file=$oViewConf->getModulePath('oeeconda','Application/views/blocks/widgets/includes/preloader.tpl')}]
                </div>
            </div>
            <script type="text/javascript">
                var widget = new econda.recengine.Widget({
                    element: '#oeEcondaRelatedProductsCrossSelling .inner',
                    renderer: {
                        type: 'template',
                        uri: '[{$oViewConf->getModuleUrl('oeeconda', $oViewConf->oeEcondaGetDetailsPageTemplate())}]'
                    },
                    accountId: '[{$oViewConf->oeEcondaGetAccountId()}]',
                    id: '[{$oViewConf->oeEcondaGetDetailsPageWidgetId()}]',
                    context: {
                        products: new Array({
                            id: '[{$oView->oeEcondaGetArticleId()}]'
                        })
                    },
                    chunkSize: 4
                });
                widget.render();
            </script>
        </div>
    [{/capture}]
[{/block}]
