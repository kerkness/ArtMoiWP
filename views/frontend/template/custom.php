<!--
============================
Customize your own template
============================

For item details:
 $details[posIndexNumber]["infoname"][0]

* infoname list:
title, caption, medium, address,
year, month, creator, tag,
width, height, depth, unit,
price, copyright, status, artmoiObjectId

* artmoiObjectId is a unique image ID
* Example (Delete the space between < and?) : <span> < ? $details[1]["creator"][0] ?> </span>

-->



<? if ($items) : ?>
    <? foreach ($items as $item)  : ?>

        <div class="col-xs-6 col-md-3">
            <div class="thumbnail">
                <a data-toggle="modal" <?if($isMobile == 1 || $isMobile == true) : ?>  href="<?= esc_url(add_query_arg( array('page_id' => $creationPostId, 'item_id' => $item->objectId)))?>" <?else:?> data-target="#myModal-<?= $item->objectId ?>" <?endif?>>
                    <img src="<?= $item->imageThumbnailUrl() ?>">
                </a>
            </div>
        </div>
    <?endforeach?>
<?endif?>

<?=$modal?>


