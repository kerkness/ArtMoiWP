<div>

    <? if($item->title) : ?>
    <div class="artmoi-title">
        <?=$item->title?> <? if($item->year):?>(<?=$item->year?>)<? endif ?>
    </div>
    <? endif ?>

    <? if( $item->edition ) : ?>
        <div>Edition: <?= $item->edition ?></div>
    <? endif ?>

    <div>
        <? if ($item->medium) : ?>
            <?= $item->medium ?>
        <? endif ?>
        <? if($item->formattedDate()) : ?>
            (<?= $item->formattedDate() ?>)
        <? endif ?>
    </div>


    <? if( $item->formattedSize() ) : ?>
        <div>
            <?= $item->formattedSize() ?>
        </div>
    <? endif ?>

    <? if (($item->status) || ($item->price)) : ?>
        <div><?= $item->status->name ?> <?= $item->price ?></div>
    <?endif?>

    <? if ($item->caption): ?>
        <div><?= $item->caption ?></div>
    <? endif ?>

</div>
