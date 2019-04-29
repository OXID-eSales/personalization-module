[{$smarty.block.parent}]

[{if $oViewConf->oePersonalizationEnableWidgets() && $oViewConf->oePersonalizationGetListPageWidgetId()}]
    [{block name="oepersonalization_page_list_listhead"}]
        [{assign var="listType" value=$oView->getListType()}]
        [{if $listType!='manufacturer' && $listType!='vendor'}]
            <div class="boxwrapper">
                <div class="page-header">
                    <h2>[{oxmultilang ident="OEPERSONALIZATION_TOP_SELLER"}]</h2>
                </div>
                <div id="oePersonalizationListHead">
                    <div class="inner list-container">
                        [{include file=$oViewConf->getModulePath('oepersonalization','Application/views/blocks/widgets/includes/preloader.tpl')}]
                    </div>
                </div>
                [{assign var="moreInfo" value='MORE_INFO'|oxmultilangassign|oxaddslashes}]
                [{assign var="widgetScript" value='
                    var lang_MORE_INFO = "'|cat:$moreInfo|cat:'";
                    (function () {
                        var widget = new econda.recengine.Widget({
                            element: "#oePersonalizationListHead .inner",
                            renderer: {
                                type: "template",
                                uri: "'|cat:$oViewConf->oePersonalizationGetListPageTemplateUrl()|cat:'"
                            },
                            accountId: "'|cat:$oViewConf->oePersonalizationGetAccountId()|cat:'",
                            id: "'|cat:$oViewConf->oePersonalizationGetListPageWidgetId()|cat:'",
                            context: {
                                categories: new Array({
                                    type: "productcategory",
                                    id: "'|cat:$oView->oePersonalizationGetCategoryId()|cat:'"
                                })
                            },
                            chunkSize: 4
                        });
                        widget.render();
                    })();
                '}]
                [{oxscript add=$widgetScript}]
            </div>
        [{/if}]
    [{/block}]
[{/if}]
