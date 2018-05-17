[{$smarty.block.parent}]

[{capture append="oxidBlock_pageHead"}]
    [{if $oViewConf->oeEcondaEnableWidgets()}]
        [{block name="oeeconda_add_js"}]
            <script type="text/javascript" src="[{$oViewConf->getModuleUrl("oeeconda", "out/js/econda-recommendations.js")}]"></script>
        [{/block}]
    [{/if}]
[{/capture}]
