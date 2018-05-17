[{$smarty.block.parent}]

[{if $oViewConf->oeEcondaEnableWidgets()}]
    [{block name="oeeconda_start_top_articles"}]
        <div class="boxwrapper">
            <div class="page-header">
                <h2>
                    [{"START_TOP_PRODUCTS_HEADER"|oxmultilangassign}]
                </h2>
                <small class="subhead">[{"START_TOP_PRODUCTS_SUBHEADER"|oxmultilangassign:4}]</small>
            </div>

            <div id="oeEcondaTopArticles">
                <div class="inner">
                    [{include file=$oViewConf->getModulePath('oeeconda','Application/views/blocks/widgets/includes/preloader.tpl')}]
                </div>
            </div>
            <script type="text/javascript">
                var widget = new econda.recengine.Widget({
                    element: '#oeEcondaTopArticles .inner',
                    renderer: {
                        type: 'template',
                        uri: '[{$oViewConf->getModuleUrl('oeeconda', $oViewConf->oeEcondaGetStartPageTopArticlesTemplate())}]'
                    },
                    accountId: '[{$oViewConf->oeEcondaGetAccountId()}]',
                    id: '[{$oViewConf->oeEcondaGetStartPageTopArticlesWidgetId()}]',
                    chunkSize: 4
                });
                widget.render();
            </script>
        </div>
    [{/block}]
[{/if}]
