[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign box="list"}]

<script type="text/javascript">
    if (parent.parent)
    {   parent.parent.sShopTitle   = "[{$actshopobj->oxshops__oxname->getRawValue()|oxaddslashes}]";
        parent.parent.sMenuItem    = "[{oxmultilang ident="mxpersonalization"}]";
        parent.parent.sMenuSubItem = "[{oxmultilang ident="tbcloepersonalizationexport"}]";
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

<form action="[{$oViewConf->getSelfLink()}]" method="post">
    [{$oViewConf->getHiddenSid()}]
    <input type="hidden" name="cl" value="[{$sClassMain}]">
    <input type="hidden" name="fnc" value="save">
    <div>
        <input type=text  class="txt" style="width: 250px;" name="confstrs[sOePersonalizationExportPath]" value="[{$sOePersonalizationExportPath}]">
        [{oxmultilang ident="SHOP_MODULE_sOePersonalizationExportPath"}]
    </div>
    <br/>
    <div>
        <input type="submit" name="save" value="[{oxmultilang ident="GENERAL_SAVE"}]">
    </div>
</form>

<br/>

<form action="[{$oViewConf->getSelfLink()}]" method="post" target="dynexport_do">
    [{$oViewConf->getHiddenSid()}]
    <input type="hidden" name="cl" value="[{$sClassDo}]">
    <input type="hidden" name="fnc" value="start">
    <div>
        <div>
            <div>[{oxmultilang ident="GENERAL_CATEGORYSELECT"}]</div>
            <select name="acat[]" size="20" multiple style="width: 210px;">
                [{foreach from=$cattree item=oCat}]
                <option value="[{$oCat->getId()}]">[{$oCat->oxcategories__oxtitle->value}]</option>
                [{/foreach}]
            </select>
        </div>
        <div>
            <input type="checkbox" name="blExportVars" value="true" checked> [{oxmultilang ident="OEPERSONALIZATION_EXPOSTVARS"}]
        </div>
        <div>
            <input type="checkbox" name="blExportMainVars" value="true" checked> [{oxmultilang ident="OEPERSONALIZATION_EXPORTMAINVARS"}]
        </div>
        <div>
            <input type="text" size="10" maxlength="10" name="sExportMinStock" value="1"> [{oxmultilang ident="OEPERSONALIZATION_EXPORTMINSTOCK"}]
        </div>
    </div>
    <br/>
    <div>
        <input type="submit" value="[{oxmultilang ident="OEPERSONALIZATION_EXPORT"}]">
    </div>
</form>

[{include file="bottomitem.tpl"}]
