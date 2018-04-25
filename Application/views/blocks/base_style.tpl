[{$smarty.block.parent}]

[{capture append="oxidBlock_pageHead"}]
    [{block name="oeeconda_add_js"}]
        <script type="text/javascript" src="[{$oViewConf->getModuleUrl("oeeconda", "out/js/econda-recommendations.js")}]"></script>
    [{/block}]
[{/capture}]
