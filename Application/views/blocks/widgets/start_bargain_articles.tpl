[{$smarty.block.parent}]

[{if $oViewConf->oePersonalizationEnableWidgets() && $oViewConf->oePersonalizationGetStartPageBargainArticlesWidgetId()}]
    [{block name="oepersonalization_start_bargain_articles"}]
        <div class="boxwrapper">
            <div class="page-header">
                <h2>
                    [{"START_BARGAIN_HEADER"|oxmultilangassign}]
                </h2>
                <small class="subhead">[{"START_BARGAIN_SUBHEADER"|oxmultilangassign}]</small>
            </div>

            <div id="oePersonalizationBargainArticles">
                <div class="inner list-container">
                    [{include file=$oViewConf->getModulePath('oepersonalization','Application/views/blocks/widgets/includes/preloader.tpl')}]
                </div>
            </div>
            <script type="text/javascript">
                [{assign var="moreInfo" value='MORE_INFO'|oxmultilangassign|oxaddslashes}]
                var lang_MORE_INFO = '[{$moreInfo}]';
                var widget = new econda.recengine.Widget({
                    element: '#oePersonalizationBargainArticles .inner',
                    renderer: {
                        type: 'template',
                        uri: '[{$oViewConf->oePersonalizationGetStartPageBargainArticlesTemplateUrl()}]'
                    },
                    accountId: '[{$oViewConf->oePersonalizationGetAccountId()}]',
                    id: '[{$oViewConf->oePersonalizationGetStartPageBargainArticlesWidgetId()}]',
                    chunkSize: 4
                });
                widget.render();
            </script>
        </div>
    [{/block}]
[{/if}]
