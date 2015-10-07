
<? if ($items) : ?>
    <? foreach ($items as $item)  : ?>

    <div class="col-xs-6 col-md-3">
        <div class="thumbnail">
            <a id="pointer-cursor" data-toggle="modal" <?if($isMobile == 1 || $isMobile == true) : ?>  href="<?= esc_url(add_query_arg( array('page_id' => $creationPostId, 'item_id' => $item->objectId)))?>" <?else:?> data-target="#myModal-<?= $item->objectId ?>" <?endif?>   >
                <img src="<?= $item->imageThumbnailUrl() ?>">
            </a>
        </div>
    </div>

    <?endforeach?>
<?endif?>

<?=$modal?>


