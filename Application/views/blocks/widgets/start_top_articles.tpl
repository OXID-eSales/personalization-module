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
            [{assign var="moreInfo" value='MORE_INFO'|oxmultilangassign|oxaddslashes}]
            [{assign var="widgetScript" value='
                var lang_MORE_INFO = "'|cat:$moreInfo|cat:'";
                (function () {
                    var widget = new econda.recengine.Widget({
                        element: "#oePersonalizationTopArticles .inner",
                        renderer: {
                            type: "template",
                            uri: "'|cat:$oViewConf->oePersonalizationGetStartPageTopArticlesTemplateUrl()|cat:'"
                        },
                        accountId: "'|cat:$oViewConf->oePersonalizationGetAccountId()|cat:'",
                        id: "'|cat:$oViewConf->oePersonalizationGetStartPageTopArticlesWidgetId()|cat:'",
                        chunkSize: 4
                    });
                    widget.render();
                })();
            '}]
            [{oxscript add=$widgetScript}]
        </div>
    [{/block}]
[{/if}]
