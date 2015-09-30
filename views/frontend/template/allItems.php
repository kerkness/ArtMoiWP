
<div>
<?foreach($alphabet as $letter) : ?>
<a href="#<?=$letter?>" style="margin-left:1em;"><? echo strtoupper($letter) ?></a>
<?endforeach?>
</div>

<? if ($details) : ?>
    <? $l = 0; ?>

    <span id="<?=$alphabet[$l]?>"> <h3> A </h3> </span>
    <ul class="list-group">
    <? for ($i = 0; $i <= $total; $i++)  : ?>

        <? if ( (strtolower(substr($details[$i]['title'][0], 0, 1)) !== $alphabet[$l]) && (substr($details[$i]['title'][0], 0, 1)) ) : ?>
            </ul>
            <? $l++; ?>
            <span id="<?=$alphabet[$l]?>"> <h3> <?echo strtoupper($alphabet[$l])?> </h3> </span>
            <ul>
        <? endif ?>

        <?if($details[$i]) : ?>
            <li class="list-group-item">
                    <a data-toggle="modal" data-target="#myModal-<?= $details[$i]['artmoiObjectId'][0] ?>">
                        <?=$details[$i]['title'][0]?><?if($details[$i]['year'][0]) : ?>, ( <?=$details[$i]['year'][0]?> )<?endif?>
                    </a>
            </li>
        <?endif?>
    <? endfor?>
    </ul>
<?endif?>



<?=$modal?>