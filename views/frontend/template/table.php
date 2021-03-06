<? if($items) : ?>
    <div class="clearfix"></div>
    <table class="table table-striped">
        <? foreach ($items as $item)  : ?>
            <? if($item && ($item->imageUrl() || $item->images[0])) :  ?>
                <tr>
                    <td class="col-md-4 col-sm-4 col-xs-4">
                        <a data-toggle="modal"  id="pointer-cursor" <?if($isMobile == 1 || $isMobile == true) : ?>  href="<?= esc_url(add_query_arg( array('page_id' => $creationPostId, 'item_id' => $item->objectId)))?>" <?else:?> data-target="#myModal-<?= $item->objectId ?>" <?endif?>> <img src="<?= ($item->images[0]->imageScaled300) ? $item->images[0]->imageScaled300 : $item->imageUrl() ?>" class="img-responsive">  </a>
                    </td>
                    <td class="col-md-4 col-sm-4 col-xs-4">
                        <? Flight::view()->render('frontend/template/listing', array('item' => $item)) ?>

                    </td>
                    <td class="text-right">
                        <a class="btn btn-default btn-sm" data-toggle="modal" <?if($isMobile == 1 || $isMobile == true) : ?>  href="<?= esc_url(add_query_arg( array('page_id' => $creationPostId, 'item_id' => $item->objectId)))?>" <?else:?> data-target="#myModal-<?= $item->objectId ?>" <?endif?> >Details</a>
                    </td>
                </tr>
            <? endif ?>
        <?endforeach?>
    </table>


<? endif ?>

<?=$modal?>
<div class="clearfix"></div>
