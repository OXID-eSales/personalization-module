[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign box="box"}]

<form action="[{$oViewConf->getSelfLink()}]" method="post">
    <input type="hidden" name="cl" value="oeecondaadmin">
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
    <br/>
    <div>
        <input type="submit" class="confinput" name="save" value="[{oxmultilang ident="GENERAL_SAVE"}]">
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
    <input type="file" name="file_to_upload">
    <input type="submit" value="[{oxmultilang ident="OEECONDA_UPLOAD_BUTTON_TITLE"}]">
</form>
<br>
<form action="[{$oViewConf->getSelfLink()}]" method="post">
    <input type="hidden" name="cl" value="oeecondaadmin">
    <input type="hidden" name="fnc" value="save">
    <div>
        <input type=hidden name="confbools[blOeEcondaTracking]" value=false>
        <input type="checkbox" name="confbools[blOeEcondaTracking]" value=true [{if ($blOeEcondaTracking)}]checked[{/if}]>
        [{oxmultilang ident="SHOP_MODULE_blOeEcondaEnableTracking"}]
    </div>
    <br/>
    <div>
        <input type="submit" value="[{oxmultilang ident="GENERAL_SAVE"}]">
    </div>
</form>

[{include file="bottomitem.tpl"}]
