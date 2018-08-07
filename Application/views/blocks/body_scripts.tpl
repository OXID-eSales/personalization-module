[{$smarty.block.parent}]

[{block name="oepersonalization_add_js_in_body"}]
    [{if $oViewConf->oePersonalizationIsTagManagerActive()}]
        <script type="text/javascript">
            if (typeof econdaData === 'undefined') {
                econdaData = {};
            }
        </script>
        <div id="econdaTMC"></div>
        <script type="text/javascript" id="econdaTM">econdaTMD=econdaData;econdatm={stored:[],results:null,
                event:function(p){this.stored.push(p);},result:function(r){this.results=r;}};
            (function(d,s){var f=d.getElementById(s),j=d.createElement('script');
                j.async=true;j.src='[{$oViewConf->oePersonalizationGetTagManagerJsFileUrl()}]';f.parentNode.insertBefore(j,f);})
            (document,'econdaTM');</script>
    [{/if}]
[{/block}]
