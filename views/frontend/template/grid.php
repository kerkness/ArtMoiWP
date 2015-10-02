<? if ($items) : ?>
    <? foreach ($items as $item)  : ?>

    <div class="col-xs-6 col-md-3">
        <div class="thumbnail">
            <a style="cursor:pointer;" data-toggle="modal" data-target="#myModal-<?= $item->objectId ?>">
                <img src="<?= $item->imageThumbnailUrl() ?>">
            </a>
        </div>
    </div>

    <?endforeach?>
<?endif?>

<?=$modal?>


