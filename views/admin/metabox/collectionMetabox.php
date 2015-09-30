<!--
 ========
 Collection Meta Box
 ========
 -->
<? if($pageType == "collection") : ?>
    <? if($collections) : ?>
        <? foreach ($collections as $collection) : ?>

            <input type="radio" name="listSelected" id="collectionSelected" value="<?="collection.".$collection->publicId?>"
                   <? if($collectionChecked == $collection->publicId) : ?>checked="checked"<? endif; ?>>
            <?= $collection->name ?>
            <? if ($collectionChecked == $collection->publicId) : ?>
                <span style="color:#E76767;"> -> this collection is linked</span>
                <input type="radio" name="listSelected" id="collectionSelected" value="<?= "delete.collection.".$collection->publicId ?>"> Unlink
            <? endif; ?>
            <br/>
        <? endforeach ?>
    <? else : ?>
        no synced collections...
    <? endif ?>
<? endif ?>