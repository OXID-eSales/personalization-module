[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign box="list"}]

<script type="text/javascript">
    if (parent.parent)
    {   parent.parent.sShopTitle   = "[{$actshopobj->oxshops__oxname->getRawValue()|oxaddslashes}]";
        parent.parent.sMenuItem    = "[{oxmultilang ident="mxpersonalization"}]";
        parent.parent.sMenuSubItem = "[{oxmultilang ident="tbcloepersonalizationtagmanager"}]";
        parent.parent.sWorkArea    = "[{$_act}]";
        parent.parent.setTitle();
    }
</script>

<script type="text/javascript">
    <!--
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

<div class="messagebox">
    [{if $oView->getTrackingScriptMessageIfEnabled()}]
    [{$oView->getTrackingScriptMessageIfEnabled()}]
    [{/if}]
    [{if $oView->getTrackingScriptMessageIfDisabled()}]
    <p class="warning">
        [{$oView->getTrackingScriptMessageIfDisabled()}]
    </p>
    [{/if}]
</div>

<form action="[{$oViewConf->getSelfLink()}]" method="post" enctype="multipart/form-data">
    [{$oViewConf->getHiddenSid()}]
    <input type="hidden" name="cl" value="[{$sClassMain}]">
    <input type="hidden" name="fnc" value="upload">
    <input type="file" name="file_to_upload">
    <input type="submit" value="[{oxmultilang ident="OEPERSONALIZATION_UPLOAD_BUTTON_TITLE"}]">
</form>
<br>
<form action="[{$oViewConf->getSelfLink()}]" method="post">
    [{$oViewConf->getHiddenSid()}]
    <input type="hidden" name="cl" value="[{$sClassMain}]">
    <input type="hidden" name="fnc" value="save">
    <div>
        <input type=hidden name="confbools[blOePersonalizationTagManager]" value=false>
        <input type="checkbox" name="confbools[blOePersonalizationTagManager]" value=true [{if ($blOePersonalizationTagManager)}]checked[{/if}]>
        [{oxmultilang ident="SHOP_MODULE_blOePersonalizationEnableTagManager"}]
    </div>
    <br/>
    <div>
        <input type="submit" value="[{oxmultilang ident="GENERAL_SAVE"}]">
    </div>
</form>

[{include file="bottomitem.tpl"}]
