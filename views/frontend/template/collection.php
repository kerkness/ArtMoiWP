
<? if($items) : ?>
    <div class="clearfix"></div>
    <div class="row">
        <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=true"></script>
        <input type="hidden" name="itemData" class="itemData" value="<?= htmlspecialchars(json_encode($items))?>" >
        <div id="map-canvas"></div>
    </div>

    <div class="margin-top-md">
        <? foreach ($items as $item ) : ?>
            <? if($item && ($item->imageUrl() || $item->imageThumbnailUrl() || $item->images[0])) :  ?>
            <!-- display items -->
            <div class="col-md-3 col-xs-6">
                <a id="pointer-cursor" class="thumbnail" data-toggle="modal" <?if($isMobile == 1 || $isMobile == true) : ?>  href="<?= esc_url(add_query_arg( array('page_id' => $creationPostId, 'item_id' => $item->objectId)))?>" <?else:?> data-target="#myModal-<?= $item->objectId ?>" <?endif?>   >
                        <img src="<?= $item->imageThumbnailUrl() ?>" />
                </a>
            </div>
            <? endif ?>
        <? endforeach ?>
    </div>

<? endif ?>
<?=$modal?>
<div class="clearfix"></div>