[{$smarty.block.parent}]

[{if $oViewConf->oeEcondaEnableWidgets()}]
    [{block name="oeeconda_start_bargain_articles"}]
        <div class="boxwrapper">
            <div class="page-header">
                <h2>
                    [{"START_BARGAIN_HEADER"|oxmultilangassign}]
                </h2>
                <small class="subhead">[{"START_BARGAIN_SUBHEADER"|oxmultilangassign}]</small>
            </div>

            <div id="oeEcondaBargainArticles">
                <div class="inner">
                    [{include file=$oViewConf->getModulePath('oeeconda','Application/views/blocks/widgets/includes/preloader.tpl')}]
                </div>
            </div>
            <script type="text/javascript">
                var widget = new econda.recengine.Widget({
                    element: '#oeEcondaBargainArticles .inner',
                    renderer: {
                        type: 'template',
                        uri: '[{$oViewConf->getModuleUrl('oeeconda', $oViewConf->oeEcondaGetStartPageBargainArticlesTemplate())}]'
                    },
                    accountId: '[{$oViewConf->oeEcondaGetAccountId()}]',
                    id: '[{$oViewConf->oeEcondaGetStartPageBargainArticlesWidgetId()}]',
                    chunkSize: 4
                });
                widget.render();
            </script>
        </div>
    [{/block}]
[{/if}]
