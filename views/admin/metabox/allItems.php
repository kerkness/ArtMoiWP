<!--
 ========
 All Items Metabox
 ========
 -->

<? if($pageType == "allItems") : ?>
    <? if ($allItems) : ?>
        <input type="radio" name="listSelected" id="allItemsSelected" value="<?="allItems"?>"
               <? if($allItemsChecked == $allItems->allItemsId) : ?>checked="checked"<? endif; ?>>
        <?=$allItems->allItemsName?>


        <? if($reportChecked == $report->objectId) : ?>
            <span style="color:#E76767;"> -> All Items group is linked</span>
            <input type="radio" name="listSelected" id="allItemsSelected" value="<?= "delete.allItems"?>"> Unlink
        <? endif; ?>
        <br/>
        <pre><?print_r($allItems)?> </pre>
    <? endif ?>
<? endif ?>
