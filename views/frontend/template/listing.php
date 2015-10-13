<div class="clearfix"></div>
<div class="margin-top-md">

    <? if($item->title) : ?>
    <div class="artmoi-title">
        <?=$item->title?> <? if($item->year):?>(<?=$item->year?>)<? endif ?>
    </div>
    <? endif ?>

    <?if($item->creator) : ?>
        <div><?=$item->creator?></div>
    <?endif?>

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
            <?= $item->formattedSize() ?> <?=$item->unit?>
        </div>
    <? endif ?>

    <? if (($item->status) || ($item->price)) : ?>
        <div><?= $item->status->name ?> <?= $item->price ?></div>
    <?endif?>

    <? if ($item->caption): ?>
        <div><?= $item->caption ?></div>
    <? endif ?>

    <? if($item->longitude && $item->latitude) : ?>
    <div class="margin-top-sm text-center">
        <div class="gmap-image">
            <img src="http://maps.googleapis.com/maps/api/staticmap?key=AIzaSyAE6zWLW7sC5fRYgtLZHxu2jAnNAzmLQX8&center=<?= $item->latitude ?>,<?= $item->longitude?>&zoom=14&scale=1&maptype=roadmap&size=400x200&markers=color:blue|<?= $item->latitude ?>, <?= $item->longitude?>">
        </div>
        <a href="http://maps.google.com/?q=<?= $item->latitude ?>,<?= $item->longitude ?>">View in Google Maps</a>
    </div>
    <?endif?>

</div>
<div class="clearfix"></div>
