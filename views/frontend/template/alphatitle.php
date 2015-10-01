<? if($items) : ?>
        <? $char = null; foreach ($items as $item) : $lastChar = strtolower( substr($item->title,0,1) ); ?>
            <? if( $lastChar != $char ) :  ?>

            <? if($char != null) : ?>
                </ul>
            <? endif ?>

            <h1><a name="<?= $lastChar ?>"></a><?= strtoupper($lastChar)?></h1>
            <ul>

            <? $char = $lastChar; endif ?>

              <li><h4><a data-toggle="modal" data-target="#myModal-<?= $item->objectId ?>"><?= $item->title ?></a><? if( $item->year ) : ?> (<?= $item->year ?>)<? endif ?></h4></li>
        <?endforeach?>
    </ul>


<? endif ?>


<?=$modal?>
