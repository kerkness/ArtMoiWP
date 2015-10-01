<? if($items) : ?>
    <table class="table table-striped">
        <? foreach ($items as $item)  : ?>
            <tr>
                <td>
                    <img src="<?= $item->imageThumbnailUrl() ?>">
                </td>
                <td>
                    <h4 style="font-style: italic; margin:5px;"><?=$item->title?> (<?=$item->year?>) </h4>
                    <?=$item->creator?>
                    <?=$item->medium?>
                    <br/>
                    <?=$item->caption?>
                </td>
                <td>
                    <a class="btn btn-default" data-toggle="modal" data-target="#myModal-<?= $item->objectId ?>">Details</a>
                </td>
            </tr>
        <?endforeach?>
    </table>


<? endif ?>

<?=$modal?>

