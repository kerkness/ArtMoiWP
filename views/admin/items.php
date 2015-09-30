<div class="wrap bootstrap-wrapper">

    <h3><?= $listTitle ?></h3>

    <p> Max 1000 items </p>

    <input type="hidden" id="pageType" value="<?= $pageType ?>">
    <input type="hidden" id="listId" value="<?= $listId ?>">
    <input type="hidden" id="listName" value="<?= $listTitle ?>">

    <button id="syncReport" class="btn btn-primary">Sync These Items</button>
    <a class="btn btn-primary" href="admin.php?page=artmoi-lists"> < Back </a>

    <div class="error_log"></div>

    <h2>Items which will be synced.</h2>


    <?if($pageType == "report") : ?>
        <?=$reportGrid?>
    <? elseif($pageType == "collection") : ?>
        <?=$collectionGrid?>
    <?endif?>

</div>






<!--<a href="?--><?//= http_build_query(array('page' => "artmoi-view-report", 'pageType' => $pageType, 'listId' => $listId, 'listTitle' => $listTitle, 'skip' => $nextSet) ) ?><!--" class="btn btn-default">Next Set</a>-->
