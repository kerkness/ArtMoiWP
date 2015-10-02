<? if($items) : ?>
    <table class="table table-striped">
        <? foreach ($items as $item)  : ?>
            <tr>
                <td>
                    <a data-toggle="modal" data-target="#myModal-<?= $item->objectId ?>"> <img class="artmoi-image artmoi-table" src="<?= $item->imageThumbnailUrl() ?>"> </a>
                </td>
                <td>

                    <? Flight::view()->render('frontend/template/listing', array('item' => $item)) ?>

                </td>
                <td>
                    <a class="btn btn-default" data-toggle="modal" data-target="#myModal-<?= $item->objectId ?>">Details</a>
                </td>
            </tr>
        <?endforeach?>
    </table>


<? endif ?>

<?=$modal?>

