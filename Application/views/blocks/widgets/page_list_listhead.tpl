[{$smarty.block.parent}]

[{block name="oeeconda_page_list_listhead"}]
    <div class="boxwrapper">
        <div class="page-header">
            <h2>[{oxmultilang ident="OEECONDA_TOP_SELLER"}]</h2>
        </div>
        <div id="oeEcondaListHead">
            <div class="inner list-container">
                [{include file=$oViewConf->getModulePath('oeeconda','Application/views/blocks/widgets/includes/preloader.tpl')}]
            </div>
        </div>
        <script type="text/javascript">
            var widget = new econda.recengine.Widget({
                element: '#oeEcondaListHead .inner',
                renderer: {
                    type: 'template',
                    uri: '[{$oViewConf->getModuleUrl('oeeconda', $oViewConf->oeEcondaGetListPageTemplate())}]'
                },
                accountId: '[{$oViewConf->oeEcondaGetAccountId()}]',
                id: '[{$oViewConf->oeEcondaGetListPageWidgetId()}]',
                context: {
                    categories: new Array({
                        type: 'productcategory',
                        id: '[{$oView->oeEcondaGetCategoryId()}]'
                    })
                },
                chunkSize: 4
            });
            widget.render();
        </script>
    </div>
[{/block}]
