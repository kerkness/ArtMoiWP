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

images[0]->imageFileIcon, images[0]->imageFileSized, image[0]->imageFileThumbnail,
images[0]->imageScaled100, images[0]->imageScaled300,  images[0]->imageScaled500,
images[0]->imageScaled750, images[0]->imageScaled1000, images[0]->imageScaled1500,
images[0]->imageSquared100, images[0]->imageSquared300, images[0]->imageSquared500,
images[0]->imageSquared50, images[0]->imageSquared1000, images[0]->imageSquared1500,

images[0]->scaledSizes->100->height, images[0]->scaledSizes->100->width, etc..


* images[0] is the main image of your item. if you have more than 1 image in an item, use images[0], images[1] and etc...
* objectId is a unique item ID


-->



<? if ($items) : ?>
    <div class="clearfix"></div>
    <? foreach ($items as $item)  : ?>
        <? if ($item) : ?>
            <div class="col-xs-6 col-md-3">
                <div class="thumbnail">
                    <a data-toggle="modal" <?if($isMobile == 1 || $isMobile == true) : ?>  href="<?= esc_url(add_query_arg( array('page_id' => $creationPostId, 'item_id' => $item->objectId)))?>" <?else:?> data-target="#myModal-<?= $item->objectId ?>" <?endif?>>
                        <img src="<?= $item->imageUrl() ?>">
                    </a>
                </div>
            </div>
        <? endif ?>
    <?endforeach?>
<?endif?>

<?=$modal?>

<div class="clearfix"></div>

