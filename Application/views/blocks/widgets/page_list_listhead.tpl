[{$smarty.block.parent}]

[{if $oViewConf->oePersonalizationEnableWidgets() && $oViewConf->oePersonalizationGetListPageWidgetId()}]
    [{block name="oepersonalization_page_list_listhead"}]
        <div class="boxwrapper">
            <div class="page-header">
                <h2>[{oxmultilang ident="OEPERSONALIZATION_TOP_SELLER"}]</h2>
            </div>
            <div id="oePersonalizationListHead">
                <div class="inner list-container">
                    [{include file=$oViewConf->getModulePath('oepersonalization','Application/views/blocks/widgets/includes/preloader.tpl')}]
                </div>
            </div>
            <script type="text/javascript">
                var lang_MORE_INFO = '[{oxmultilang ident="MORE_INFO"}]';
                var widget = new econda.recengine.Widget({
                    element: '#oePersonalizationListHead .inner',
                    renderer: {
                        type: 'template',
                        uri: '[{$oViewConf->getModuleUrl('oepersonalization', $oViewConf->oePersonalizationGetListPageTemplate())}]'
                    },
                    accountId: '[{$oViewConf->oePersonalizationGetAccountId()}]',
                    id: '[{$oViewConf->oePersonalizationGetListPageWidgetId()}]',
                    context: {
                        categories: new Array({
                            type: 'productcategory',
                            id: '[{$oView->oePersonalizationGetCategoryId()}]'
                        })
                    },
                    chunkSize: 4
                });
                widget.render();
            </script>
        </div>
    [{/block}]
[{/if}]