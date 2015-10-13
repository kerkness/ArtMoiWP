<? if($items) : ?>
<div class="clearfix"></div>
        <? $char = null; foreach ($items as $item) : $lastChar = strtolower( substr($item->title,0,1) ); ?>

            <? if( $lastChar != $char ) :  ?>

                <? if($char != null) : ?>
                    </ul>
                <? endif ?>

            <h1><a name="<?= $lastChar ?>"></a><?= strtoupper($lastChar)?></h1>

            <ul>

            <? $char = $lastChar; endif ?>
              <li><h4 id="pointer-cursor"><a data-toggle="modal" <?if($isMobile == 1 || $isMobile == true) : ?>  href="<?= esc_url(add_query_arg( array('page_id' => $creationPostId, 'item_id' => $item->objectId)))?>" <?else:?> data-target="#myModal-<?= $item->objectId ?>" <?endif?> ><?= $item->title ?></a><? if( $item->year ) : ?> (<?= $item->year ?>)<? endif ?></h4></li>

        <?endforeach?>
    </ul>

<? endif ?>
<?=$modal?>

<div class="clearfix"></div>



<!--<pre>--><?//print_r($items)?><!--</pre>-->