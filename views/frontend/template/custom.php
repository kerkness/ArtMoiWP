<!--
============================
Customize your own template
============================

How to use: remove two slashes (//)
<//?= $item->PUTSOMETHINGHERE ?>

* data list :
title, caption, medium, address,
year, month, creator, tag,
width, height, depth, unit,
price, copyright, status, objectId,
city, country, latitude, longitude

* Size:
formattedSize()

* Image:
imageUrl(), imageThumbnailUrl()

* objectId is a unique item ID


-->



<? if ($items) : ?>
    <div class="clearfix"></div>
    <? foreach ($items as $item)  : ?>
        <? if ($item) : ?>
            <div class="col-xs-6 col-md-3">
                <div class="thumbnail">
                    <a data-toggle="modal" <?if($isMobile == 1 || $isMobile == true) : ?>  href="<?= esc_url(add_query_arg( array('page_id' => $creationPostId, 'item_id' => $item->objectId)))?>" <?else:?> data-target="#myModal-<?= $item->objectId ?>" <?endif?>>
                        <img src="<?= $item->imageThumbnailUrl() ?>">
                    </a>
                </div>
            </div>
        <? endif ?>
    <?endforeach?>
<?endif?>

<?=$modal?>

<div class="clearfix"></div>

