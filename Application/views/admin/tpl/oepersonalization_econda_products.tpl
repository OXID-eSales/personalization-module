<hr>

<section>
    <h1>[{oxmultilang ident="OEPERSONALIZATION_ANALYTICS_HEADING"}]</h1>
    <ul>
        [{section name=item start=1 loop=7}]
            [{assign var="item" value=$smarty.section.item.index}]
            <li>
                [{oxmultilang ident="OEPERSONALIZATION_ANALYTICS_ITEM_"|cat:$item}]
            </li>
        [{/section}]
    </ul>
</section>

<section>
    <h1>[{oxmultilang ident="OEPERSONALIZATION_CROSS_SELL_HEADING"}]</h1>
    <ul>
        [{section name=item start=1 loop=5}]
        [{assign var="item" value=$smarty.section.item.index}]
        <li>
            [{oxmultilang ident="OEPERSONALIZATION_CROSS_SELL_ITEM_"|cat:$item}]
        </li>
        [{/section}]
    </ul>
</section>

<section>
    <h1>[{oxmultilang ident="OEPERSONALIZATION_RELATIONSHIP_PLATFORM_HEADING"}]</h1>
    <ul>
        [{section name=item start=1 loop=6}]
        [{assign var="item" value=$smarty.section.item.index}]
        <li>
            [{oxmultilang ident="OEPERSONALIZATION_RELATIONSHIP_PLATFORM_ITEM_"|cat:$item}]
        </li>
        [{/section}]
    </ul>
</section>