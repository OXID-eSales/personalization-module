[{$smarty.block.parent}]

[{if $oViewConf->oePersonalizationEnableWidgets() && $oViewConf->oePersonalizationGetStartPageTopArticlesWidgetId()}]
    [{block name="oepersonalization_start_top_articles"}]
        <div class="boxwrapper">
            <div class="page-header">
                <h2>
                    [{"START_TOP_PRODUCTS_HEADER"|oxmultilangassign}]
                </h2>
                <small class="subhead">[{"START_TOP_PRODUCTS_SUBHEADER"|oxmultilangassign:4}]</small>
            </div>

            <div id="oePersonalizationTopArticles">
                <div class="inner list-container">
                    [{include file=$oViewConf->getModulePath('oepersonalization','Application/views/blocks/widgets/includes/preloader.tpl')}]
                </div>
            </div>
            <script type="text/javascript">
                var lang_MORE_INFO = '[{oxmultilang ident="MORE_INFO"}]';
                var widget = new econda.recengine.Widget({
                    element: '#oePersonalizationTopArticles .inner',
                    renderer: {
                        type: 'template',
                        uri: '[{$oViewConf->getModuleUrl('oepersonalization', $oViewConf->oePersonalizationGetStartPageTopArticlesTemplate())}]'
                    },
                    accountId: '[{$oViewConf->oePersonalizationGetAccountId()}]',
                    id: '[{$oViewConf->oePersonalizationGetStartPageTopArticlesWidgetId()}]',
                    chunkSize: 4
                });
                widget.render();
            </script>
        </div>
    [{/block}]
[{/if}]
