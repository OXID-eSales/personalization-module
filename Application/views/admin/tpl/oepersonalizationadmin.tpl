[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign box="box"}]

<form action="[{$oViewConf->getSelfLink()}]" method="post">
    [{$oViewConf->getHiddenSid()}]
    <input type="hidden" name="cl" value="[{$sClassMain}]">
    <input type="hidden" name="fnc" value="save">
    <h3>[{oxmultilang ident="SHOP_MODULE_GROUP_oepersonalization_account"}]</h3>
    <div>
        <input type=text  class="txt" style="width: 250px;" name="confstrs[sOePersonalizationAccountId]" value="[{$sOePersonalizationAccountId}]">
        [{oxmultilang ident="SHOP_MODULE_sOePersonalizationAccountId"}]
    </div>
    <div>
        <input type=hidden name="confbools[blOePersonalizationEnableWidgets]" value=false>
        <input type=checkbox name="confbools[blOePersonalizationEnableWidgets]" value=true [{if ($blOePersonalizationEnableWidgets)}]checked[{/if}]>
        [{oxmultilang ident="SHOP_MODULE_blOePersonalizationEnableWidgets"}]
    </div>
    <div>
        <input type=hidden name="confbools[blOePersonalizationUseDemoAccount]" value=false>
        <input type=checkbox name="confbools[blOePersonalizationUseDemoAccount]" value=true [{if ($blOePersonalizationUseDemoAccount)}]checked[{/if}]>
        [{oxmultilang ident="SHOP_MODULE_blOePersonalizationUseDemoAccount"}]
    </div>
    <h3>[{oxmultilang ident="SHOP_MODULE_GROUP_oepersonalization_start_page_widgets"}]</h3>
    <div>
        <input type=text  class="txt" style="width: 250px;" name="confstrs[sOePersonalizationWidgetIdStartPageBargainArticles]" value="[{$sOePersonalizationWidgetIdStartPageBargainArticles}]">
        [{oxmultilang ident="SHOP_MODULE_sOePersonalizationWidgetIdStartPageBargainArticles"}]
    </div>
    <div>
        <input type=text  class="txt" style="width: 250px;" name="confstrs[sOePersonalizationWidgetTemplateStartPageBargainArticles]" value="[{$sOePersonalizationWidgetTemplateStartPageBargainArticles}]">
        [{oxmultilang ident="SHOP_MODULE_sOePersonalizationWidgetTemplateStartPageBargainArticles"}]
    </div>
    <br/>
    <div>
        <input type=text  class="txt" style="width: 250px;" name="confstrs[sOePersonalizationWidgetIdStartPageTopArticles]" value="[{$sOePersonalizationWidgetIdStartPageTopArticles}]">
        [{oxmultilang ident="SHOP_MODULE_sOePersonalizationWidgetIdStartPageTopArticles"}]
    </div>
    <div>
        <input type=text  class="txt" style="width: 250px;" name="confstrs[sOePersonalizationWidgetTemplateStartPageTopArticles]" value="[{$sOePersonalizationWidgetTemplateStartPageTopArticles}]">
        [{oxmultilang ident="SHOP_MODULE_sOePersonalizationWidgetTemplateStartPageTopArticles"}]
    </div>
    <h3>[{oxmultilang ident="SHOP_MODULE_GROUP_oepersonalization_list_page_widgets"}]</h3>
    <div>
        <input type=text  class="txt" style="width: 250px;" name="confstrs[sOePersonalizationWidgetIdListPage]" value="[{$sOePersonalizationWidgetIdListPage}]">
        [{oxmultilang ident="SHOP_MODULE_sOePersonalizationWidgetIdListPage"}]
    </div>
    <div>
        <input type=text  class="txt" style="width: 250px;" name="confstrs[sOePersonalizationWidgetTemplateListPage]" value="[{$sOePersonalizationWidgetTemplateListPage}]">
        [{oxmultilang ident="SHOP_MODULE_sOePersonalizationWidgetTemplateListPage"}]
    </div>
    <h3>[{oxmultilang ident="SHOP_MODULE_GROUP_oepersonalization_details_page_widgets"}]</h3>
    <div>
        <input type=text  class="txt" style="width: 250px;" name="confstrs[sOePersonalizationWidgetIdDetailsPage]" value="[{$sOePersonalizationWidgetIdDetailsPage}]">
        [{oxmultilang ident="SHOP_MODULE_sOePersonalizationWidgetIdDetailsPage"}]
    </div>
    <div>
        <input type=text  class="txt" style="width: 250px;" name="confstrs[sOePersonalizationWidgetTemplateDetailsPage]" value="[{$sOePersonalizationWidgetTemplateDetailsPage}]">
        [{oxmultilang ident="SHOP_MODULE_sOePersonalizationWidgetTemplateDetailsPage"}]
    </div>
    <h3>[{oxmultilang ident="SHOP_MODULE_GROUP_oepersonalization_thank_you_page_widgets"}]</h3>
    <div>
        <input type=text  class="txt" style="width: 250px;" name="confstrs[sOePersonalizationWidgetIdThankYouPage]" value="[{$sOePersonalizationWidgetIdThankYouPage}]">
        [{oxmultilang ident="SHOP_MODULE_sOePersonalizationWidgetIdThankYouPage"}]
    </div>
    <div>
        <input type=text  class="txt" style="width: 250px;" name="confstrs[sOePersonalizationWidgetTemplateThankYouPage]" value="[{$sOePersonalizationWidgetTemplateThankYouPage}]">
        [{oxmultilang ident="SHOP_MODULE_sOePersonalizationWidgetTemplateThankYouPage"}]
    </div>
    <h3>[{oxmultilang ident="SHOP_MODULE_GROUP_oepersonalization_export"}]</h3>
    <div>
        <input type=text  class="txt" style="width: 250px;" name="confstrs[sOePersonalizationExportPath]" value="[{$sOePersonalizationExportPath}]">
        [{oxmultilang ident="SHOP_MODULE_sOePersonalizationExportPath"}]
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
    <h3>[{oxmultilang ident="OEPERSONALIZATION_EXPORT"}]</h3>
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
        <input type="submit" value="[{oxmultilang ident="OEPERSONALIZATION_EXPORT"}]">
    </div>
</form>

<hr>

<h3>[{oxmultilang ident="OEPERSONALIZATION_TRACKING_SECTION_TITLE"}]</h3>

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
