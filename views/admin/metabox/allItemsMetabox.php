<!--
 ========
 All Items Metabox
 ========
 -->

<? if($pageType == "allItems") : ?>
    <? if ($allItems) : ?>
        <input type="radio" name="listSelected" id="allItemsSelected" value="<?="allItems"?>"
        <?if($allItemsChecked) : ?><? if($allItemsChecked == $allItems->allItemsId) : ?>checked="checked"<? endif; ?><? endif; ?>>
        <?=$allItems->allItemsName?>

       <?if($allItemsChecked) : ?>
            <? if($allItemsChecked  ==  $allItems->allItemsId) : ?>
                <span style="color:#E76767;"> -> All Items group is linked</span>
                <input type="radio" name="listSelected" id="allItemsSelected" value="<?= "delete.allItems"?>"> Unlink
            <? endif ?>
        <?endif?>

    <? else: ?>
        no synced group...
    <? endif ?>

<? endif ?>
