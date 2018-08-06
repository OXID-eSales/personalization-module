[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign box="list"}]

<script type="text/javascript">
    if (parent.parent)
    {   parent.parent.sShopTitle   = "[{$actshopobj->oxshops__oxname->getRawValue()|oxaddslashes}]";
        parent.parent.sMenuItem    = "[{oxmultilang ident="mxpersonalization"}]";
        parent.parent.sMenuSubItem = "[{oxmultilang ident="tbcloepersonalizationwidgets"}]";
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

<form action="[{$oViewConf->getSelfLink()}]" method="post">
    [{$oViewConf->getHiddenSid()}]
    <input type="hidden" name="cl" value="[{$sClassMain}]">
    <input type="hidden" name="fnc" value="save">
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
    <br/>
    <div>
        <input type=hidden name="confbools[blOePersonalizationEnableWidgets]" value=false>
        <input type=checkbox name="confbools[blOePersonalizationEnableWidgets]" value=true [{if ($blOePersonalizationEnableWidgets)}]checked[{/if}]>
        [{oxmultilang ident="SHOP_MODULE_blOePersonalizationEnableWidgets"}]
    </div>
    <br/>
    <div>
        <input type="submit" name="save" value="[{oxmultilang ident="GENERAL_SAVE"}]">
    </div>
</form>

[{include file="bottomitem.tpl"}]

