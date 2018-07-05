[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign box="box"}]

<form action="[{$oViewConf->getSelfLink()}]" method="post">
    [{$oViewConf->getHiddenSid()}]
    <input type="hidden" name="cl" value="[{$sClassMain}]">
    <input type="hidden" name="fnc" value="save">
    <h3>[{oxmultilang ident="SHOP_MODULE_GROUP_oeeconda_account"}]</h3>
    <div>
        <input type=text  class="txt" style="width: 250px;" name="confstrs[sOeEcondaAccountId]" value="[{$sOeEcondaAccountId}]">
        [{oxmultilang ident="SHOP_MODULE_sOeEcondaAccountId"}]
    </div>
    <div>
        <input type=hidden name="confbools[blOeEcondaEnableWidgets]" value=false>
        <input type=checkbox name="confbools[blOeEcondaEnableWidgets]" value=true [{if ($blOeEcondaEnableWidgets)}]checked[{/if}]>
        [{oxmultilang ident="SHOP_MODULE_blOeEcondaEnableWidgets"}]
    </div>
    <div>
        <input type=hidden name="confbools[blOeEcondaUseDemoAccount]" value=false>
        <input type=checkbox name="confbools[blOeEcondaUseDemoAccount]" value=true [{if ($blOeEcondaUseDemoAccount)}]checked[{/if}]>
        [{oxmultilang ident="SHOP_MODULE_blOeEcondaUseDemoAccount"}]
    </div>
    <h3>[{oxmultilang ident="SHOP_MODULE_GROUP_oeeconda_start_page_widgets"}]</h3>
    <div>
        <input type=text  class="txt" style="width: 250px;" name="confstrs[sOeEcondaWidgetIdStartPageBargainArticles]" value="[{$sOeEcondaWidgetIdStartPageBargainArticles}]">
        [{oxmultilang ident="SHOP_MODULE_sOeEcondaWidgetIdStartPageBargainArticles"}]
    </div>
    <div>
        <input type=text  class="txt" style="width: 250px;" name="confstrs[sOeEcondaWidgetTemplateStartPageBargainArticles]" value="[{$sOeEcondaWidgetTemplateStartPageBargainArticles}]">
        [{oxmultilang ident="SHOP_MODULE_sOeEcondaWidgetTemplateStartPageBargainArticles"}]
    </div>
    <br/>
    <div>
        <input type=text  class="txt" style="width: 250px;" name="confstrs[sOeEcondaWidgetIdStartPageTopArticles]" value="[{$sOeEcondaWidgetIdStartPageTopArticles}]">
        [{oxmultilang ident="SHOP_MODULE_sOeEcondaWidgetIdStartPageTopArticles"}]
    </div>
    <div>
        <input type=text  class="txt" style="width: 250px;" name="confstrs[sOeEcondaWidgetTemplateStartPageTopArticles]" value="[{$sOeEcondaWidgetTemplateStartPageTopArticles}]">
        [{oxmultilang ident="SHOP_MODULE_sOeEcondaWidgetTemplateStartPageTopArticles"}]
    </div>
    <h3>[{oxmultilang ident="SHOP_MODULE_GROUP_oeeconda_list_page_widgets"}]</h3>
    <div>
        <input type=text  class="txt" style="width: 250px;" name="confstrs[sOeEcondaWidgetIdListPage]" value="[{$sOeEcondaWidgetIdListPage}]">
        [{oxmultilang ident="SHOP_MODULE_sOeEcondaWidgetIdListPage"}]
    </div>
    <div>
        <input type=text  class="txt" style="width: 250px;" name="confstrs[sOeEcondaWidgetTemplateListPage]" value="[{$sOeEcondaWidgetTemplateListPage}]">
        [{oxmultilang ident="SHOP_MODULE_sOeEcondaWidgetTemplateListPage"}]
    </div>
    <h3>[{oxmultilang ident="SHOP_MODULE_GROUP_oeeconda_details_page_widgets"}]</h3>
    <div>
        <input type=text  class="txt" style="width: 250px;" name="confstrs[sOeEcondaWidgetIdDetailsPage]" value="[{$sOeEcondaWidgetIdDetailsPage}]">
        [{oxmultilang ident="SHOP_MODULE_sOeEcondaWidgetIdDetailsPage"}]
    </div>
    <div>
        <input type=text  class="txt" style="width: 250px;" name="confstrs[sOeEcondaWidgetTemplateDetailsPage]" value="[{$sOeEcondaWidgetTemplateDetailsPage}]">
        [{oxmultilang ident="SHOP_MODULE_sOeEcondaWidgetTemplateDetailsPage"}]
    </div>
    <h3>[{oxmultilang ident="SHOP_MODULE_GROUP_oeeconda_thank_you_page_widgets"}]</h3>
    <div>
        <input type=text  class="txt" style="width: 250px;" name="confstrs[sOeEcondaWidgetIdThankYouPage]" value="[{$sOeEcondaWidgetIdThankYouPage}]">
        [{oxmultilang ident="SHOP_MODULE_sOeEcondaWidgetIdThankYouPage"}]
    </div>
    <div>
        <input type=text  class="txt" style="width: 250px;" name="confstrs[sOeEcondaWidgetTemplateThankYouPage]" value="[{$sOeEcondaWidgetTemplateThankYouPage}]">
        [{oxmultilang ident="SHOP_MODULE_sOeEcondaWidgetTemplateThankYouPage"}]
    </div>
    <h3>[{oxmultilang ident="SHOP_MODULE_GROUP_oeeconda_export"}]</h3>
    <div>
        <input type=text  class="txt" style="width: 250px;" name="confstrs[sOeEcondaExportPath]" value="[{$sOeEcondaExportPath}]">
        [{oxmultilang ident="SHOP_MODULE_sOeEcondaExportPath"}]
    </div>
    <br/>
    <div>
        <input type="submit" name="save" value="[{oxmultilang ident="GENERAL_SAVE"}]">
    </div>
</form>

<form action="[{$oViewConf->getSelfLink()}]" method="post" target="dynexport_do">
    [{$oViewConf->getHiddenSid()}]
    <input type="hidden" name="cl" value="[{$sClassDo}]">
    <input type="hidden" name="fnc" value="start">
    <h3>[{oxmultilang ident="OEECONDA_EXPORT"}]</h3>
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
            [{oxmultilang ident="GENERAL_EXPOSTVARS"}] <input type="checkbox" name="blExportVars" value="true" checked>
        </div>
        <div>
            [{oxmultilang ident="GENERAL_EXPORTMAINVARS"}] <input type="checkbox" name="blExportMainVars" value="true" checked>
        </div>
        <div>
            [{oxmultilang ident="GENERAL_EXPORTMINSTOCK"}] <input type="text" size="10" maxlength="10" name="sExportMinStock" value="1">
        </div>
    </div>
    <div>
        <input type="submit" value="[{oxmultilang ident="OEECONDA_EXPORT"}]">
    </div>
</form>

<hr>

<h3>[{oxmultilang ident="OEECONDA_TRACKING_SECTION_TITLE"}]</h3>

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

<form action="[{$oViewConf->getSelfLink()}]cl=oeecondaemosjsupload&fnc=upload" method="post" enctype="multipart/form-data">
    [{$oViewConf->getHiddenSid()}]
    <input type="file" name="file_to_upload">
    <input type="submit" value="[{oxmultilang ident="OEECONDA_UPLOAD_BUTTON_TITLE"}]">
</form>
<br>
<form action="[{$oViewConf->getSelfLink()}]" method="post">
    [{$oViewConf->getHiddenSid()}]
    <input type="hidden" name="cl" value="[{$sClassMain}]">
    <input type="hidden" name="fnc" value="save">
    <div>
        <input type=hidden name="confbools[blOeEcondaTracking]" value=false>
        <input type="checkbox" name="confbools[blOeEcondaTracking]" value=true [{if ($blOeEcondaTracking)}]checked[{/if}]>
        [{oxmultilang ident="SHOP_MODULE_blOeEcondaEnableTracking"}]
    </div>
    <h4>[{oxmultilang ident="SHOP_MODULE_sOeEcondaTrackingShowNote"}]</h4>
    <div class="messagebox">
        [{oxmultilang ident="OEECONDA_MESSAGE_CMS_SNIPPETS"}]
    </div>
    <div>
        <select size="1" name="confstrs[sOeEcondaTrackingShowNote]">
            <option value="no"[{if $sOeEcondaTrackingShowNote == 'no'}] selected[{/if}]>[{oxmultilang ident="SHOP_MODULE_sOeEcondaTrackingShowNoteNo"}]</option>
            <option value="opt_in"[{if $sOeEcondaTrackingShowNote == 'opt_in'}] selected[{/if}]>[{oxmultilang ident="SHOP_MODULE_sOeEcondaTrackingShowNoteOptIn"}]</option>
            <option value="opt_out"[{if $sOeEcondaTrackingShowNote == 'opt_out'}] selected[{/if}]>[{oxmultilang ident="SHOP_MODULE_sOeEcondaTrackingShowNoteOptOut"}]</option>
        </select>
    </div>
    <br/>
    <div>
        <input type="submit" value="[{oxmultilang ident="GENERAL_SAVE"}]">
    </div>
</form>

[{include file="bottomitem.tpl"}]
