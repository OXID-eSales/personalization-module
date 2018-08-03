[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign box="list"}]

<script type="text/javascript">
    if (parent.parent)
    {   parent.parent.sShopTitle   = "[{$actshopobj->oxshops__oxname->getRawValue()|oxaddslashes}]";
        parent.parent.sMenuItem    = "[{oxmultilang ident="mxpersonalization"}]";
        parent.parent.sMenuSubItem = "[{oxmultilang ident="tbcloepersonalizationtracking"}]";
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

<form action="[{$oViewConf->getSelfLink()}]cl=oepersonalizationemosjsupload&fnc=upload" method="post" enctype="multipart/form-data">
    [{$oViewConf->getHiddenSid()}]
    <input type="file" name="file_to_upload">
    <input type="submit" value="[{oxmultilang ident="OEPERSONALIZATION_UPLOAD_BUTTON_TITLE"}]">
</form>
<br>
<form action="[{$oViewConf->getSelfLink()}]" method="post">
    [{$oViewConf->getHiddenSid()}]
    <input type="hidden" name="cl" value="[{$sClassMain}]">
    <input type="hidden" name="fnc" value="save">
    <div>
        <input type=hidden name="confbools[blOePersonalizationTracking]" value=false>
        <input type="checkbox" name="confbools[blOePersonalizationTracking]" value=true [{if ($blOePersonalizationTracking)}]checked[{/if}]>
        [{oxmultilang ident="SHOP_MODULE_blOePersonalizationEnableTracking"}]
    </div>
    <h4>[{oxmultilang ident="SHOP_MODULE_sOePersonalizationTrackingShowNote"}]</h4>
    <div class="messagebox">
        [{oxmultilang ident="OEPERSONALIZATION_MESSAGE_CMS_SNIPPETS"}]
    </div>
    <div>
        <select size="1" name="confstrs[sOePersonalizationTrackingShowNote]">
            <option value="no"[{if $sOePersonalizationTrackingShowNote == 'no'}] selected[{/if}]>[{oxmultilang ident="SHOP_MODULE_sOePersonalizationTrackingShowNoteNo"}]</option>
            <option value="opt_in"[{if $sOePersonalizationTrackingShowNote == 'opt_in'}] selected[{/if}]>[{oxmultilang ident="SHOP_MODULE_sOePersonalizationTrackingShowNoteOptIn"}]</option>
            <option value="opt_out"[{if $sOePersonalizationTrackingShowNote == 'opt_out'}] selected[{/if}]>[{oxmultilang ident="SHOP_MODULE_sOePersonalizationTrackingShowNoteOptOut"}]</option>
        </select>
    </div>
    <br/>
    <div>
        <input type="submit" value="[{oxmultilang ident="GENERAL_SAVE"}]">
    </div>
</form>

[{include file="bottomitem.tpl"}]

