[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign box="list"}]

<script type="text/javascript">
    if (parent.parent)
    {   parent.parent.sShopTitle   = "[{$actshopobj->oxshops__oxname->getRawValue()|oxaddslashes}]";
        parent.parent.sMenuItem    = "[{oxmultilang ident="mxpersonalization"}]";
        parent.parent.sMenuSubItem = "[{oxmultilang ident="tbcloepersonalizationgeneral"}]";
        parent.parent.sWorkArea    = "[{$_act}]";
        parent.parent.setTitle();
    }
</script>

<script type="text/javascript">
    <!--
    function changeEditBar( sLocation, sPos )
    {
        parent.edit.location = '[{$oViewConf->getSelfLink()|replace:"&amp;":"&"}]&cl=' + sLocation;

        var oSearch = document.getElementById("search");
        oSearch.actedit.value = sPos;
        oSearch.submit();
    }
    window.onload = function ()
    {
        top.reloadEditFrame();
        [{if $updatelist == 1}]
        top.oxid.admin.updateList('[{$oxid}]');
        [{/if}]
    }
    //-->
</script>

<br/>

<form name="search" id="search" action="[{$oViewConf->getSelfLink()}]" method="post">
    [{$oViewConf->getHiddenSid()}]
    <input type="hidden" name="cl" value="oepersonalizationgeneral">
    <input type="hidden" name="actedit" value="[{$actedit}]">
    <input type="hidden" name="oxid" value="1">
    <input type="hidden" name="fnc" value="">
    <input type="hidden" name="menu" value="[{$menu}]">
</form>

<form action="[{$oViewConf->getSelfLink()}]" method="post">
    [{$oViewConf->getHiddenSid()}]
    <input type="hidden" name="cl" value="[{$sClassMain}]">
    <input type="hidden" name="fnc" value="save">
    <div>
        <input type=text  class="txt" style="width: 250px;" name="confstrs[sOePersonalizationAccountId]" value="[{$sOePersonalizationAccountId}]">
        [{oxmultilang ident="SHOP_MODULE_sOePersonalizationAccountId"}]
    </div>
    <div>
        <input type=hidden name="confbools[blOePersonalizationUseDemoAccount]" value=false>
        <input type=checkbox name="confbools[blOePersonalizationUseDemoAccount]" value=true [{if ($blOePersonalizationUseDemoAccount)}]checked[{/if}]>
        [{oxmultilang ident="SHOP_MODULE_blOePersonalizationUseDemoAccount"}]
    </div>
    <br/>
    <div>
        <input type="submit" name="save" value="[{oxmultilang ident="GENERAL_SAVE"}]">
    </div>
</form>

[{include file="pagetabsnippet.tpl" noOXIDCheck="true" sEditAction="changeEditBar"}]

[{include file="bottomitem.tpl"}]
