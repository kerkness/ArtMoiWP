<? if ($details) : ?>
    <table class="table table-striped">
        <? for ($i = $total-1; $i >= 0; $i--)  : ?>
            <tr>
                <td>
                    <img src="<?= $thumbnailImages[$i] ?>">
                </td>
                <td>
                    <h4 style="font-style: italic; margin:5px;"><?=$details[$i]['title'][0]?> (<?=$details[$i]['year'][0]?>) </h4>
                    <?=$details[$i]['creator'][0]?>
                    <?=$details[$i]['medium'][0]?>
                    <br/>
                    <?=$details[$i]['caption'][0]?>
                </td>
                <td>
                    <a class="btn btn-default" data-toggle="modal" data-target="#myModal-<?= $details[$i]['artmoiObjectId'][0] ?>">Details</a>
                </td>
            </tr>
        <?endfor?>
    </table>
<?endif?>

<? if($items) : ?>
    <table class="table table-striped">
        <? foreach ($items as $item)  : ?>
            <tr>
                <td>
                    <img src="<?= $item->images[0]->imageFileThumbnail ?>">
                </td>
                <td>
                    <h4 style="font-style: italic; margin:5px;"><?=$details[$i]['title'][0]?> (<?=$details[$i]['year'][0]?>) </h4>
                    <?=$details[$i]['creator'][0]?>
                    <?=$details[$i]['medium'][0]?>
                    <br/>
                    <?=$details[$i]['caption'][0]?>
                </td>
                <td>
                    <a class="btn btn-default" data-toggle="modal" data-target="#myModal-<?= $details[$i]['artmoiObjectId'][0] ?>">Details</a>
                </td>
            </tr>
        <?endforeach?>
    </table>

    <pre>
        <? print_r($item) ?>
    </pre>

<? endif ?>

<?=$modal?>

