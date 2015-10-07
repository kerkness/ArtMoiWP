<? if($items) : ?>
    <table class="table table-striped">
        <? foreach ($items as $item)  : ?>
            <tr>
                <td>
                    <a data-toggle="modal"  id="pointer-cursor" <?if($isMobile == 1 || $isMobile == true) : ?>  href="<?= esc_url(add_query_arg( array('page_id' => $creationPostId, 'item_id' => $item->objectId)))?>" <?else:?> data-target="#myModal-<?= $item->objectId ?>" <?endif?>> <img class="artmoi-image artmoi-table" src="<?= $item->imageThumbnailUrl() ?>"> </a>
                </td>
                <td>

                    <? Flight::view()->render('frontend/template/listing', array('item' => $item)) ?>

                </td>
                <td>
                    <a class="btn btn-default" data-toggle="modal" <?if($isMobile == 1 || $isMobile == true) : ?>  href="<?= esc_url(add_query_arg( array('page_id' => $creationPostId, 'item_id' => $item->objectId)))?>" <?else:?> data-target="#myModal-<?= $item->objectId ?>" <?endif?> >Details</a>
                </td>
            </tr>
        <?endforeach?>
    </table>


<? endif ?>

<?=$modal?>

