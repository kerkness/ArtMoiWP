<? if ($details) : ?>
    <? for ($i = $total-1; $i >= 0; $i--)  : ?>

    <div class="col-xs-6 col-md-3">
        <div class="thumbnail">
            <a data-toggle="modal" data-target="#myModal-<?= $details[$i]['artmoiObjectId'][0] ?>">
                <img src="<?= $thumbnailImages[$i] ?>">
            </a>
        </div>
    </div>

    <?endfor?>
<?endif?>

<?=$modal?>


