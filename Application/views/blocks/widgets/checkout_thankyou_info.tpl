[{$smarty.block.parent}]

[{block name="oeeconda_checkout_thankyou_info"}]
    <div class="boxwrapper">
        <div class="page-header">
            <h2>[{oxmultilang ident="OEECONDA_MIGHT_INTEREST"}]</h2>
        </div>
        <div id="oeEcondaThankYouInfo">
            <div class="inner list-container">
                [{include file=$oViewConf->getModulePath('oeeconda','Application/views/blocks/widgets/includes/preloader.tpl')}]
            </div>
        </div>
        <script type="text/javascript">
            var widget = new econda.recengine.Widget({
                element: '#oeEcondaThankYouInfo .inner',
                renderer: {
                    type: 'template',
                    uri: '[{$oViewConf->getModuleUrl('oeeconda', $oViewConf->oeEcondaGetThankYouPageTemplate())}]'
                },
                accountId: '[{$oViewConf->oeEcondaGetAccountId()}]',
                id: '[{$oViewConf->oeEcondaGetThankYouPageWidgetId()}]',
                chunkSize: 4,
                autoContext: true
            });
            widget.render();
        </script>
    </div>
[{/block}]
