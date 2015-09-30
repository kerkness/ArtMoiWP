<div class="modal fade" id="myModal-<?= $item->objectId ?>" imgsrc="<?= $item->images[0]->imageFileSized ?>">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h2 class="modal-title"><? if ($item->title) : ?><?= $item->title ?><? else: ?> Untitled<? endif ?>
                </h2>
            </div>
            <div class="modal-body">

                <img id="img-myModal-<?= $item->objectId ?>"/>

                <div>

                    <div style="font-style: italic;">
                        <? if ($item->title) : ?><?= $item->title ?><? else: ?> Untitled<? endif ?>
                    </div>

                    <? if( $item->editionSize || $item->editionNumber ) : ?>
                    <div>
                        Edition: <? if($item->editionNumber) : ?><?= $item->editionNumber ?><? endif ?><? if($item->editionSize && $item->editionNumber) :?>/<? endif ?><? if($item->editionSize) : ?><?= $item->editionSize ?><? endif ?>
                    </div>
                    <? endif ?>

                    <div><? if ($item->medium && $item->medium->name) : ?>
                            <?= $item->medium->name ?>
                     <? endif ?><? if($item->creationDate && $item->creationDate->month) : ?>, <?= $item->creationDate->month ?><? endif ?> <? if( $item->creationDate->year ) : ?> (<?= $item->creationDate->year ?>)<? endif ?></div>


                    <? if( $item->size ) : ?>
                        <div>
                            <? if($item->size->height) :?><?= $item->size->height ?><? endif ?>
                            <? if($item->size->height && $item->size->width) :?> x <? endif ?>
                            <? if($item->size->width) :?><?= $item->size->width ?><? endif ?>
                            <? if(($item->size->height || $item->size->width) && $item->size->depth) :?> x <? endif ?>
                            <? if($item->size->depth) :?><?= $item->size->depth ?><? endif ?>

                            <? if($item->size->units) :?><?= strtoupper($item->size->units->value) ?><? endif ?>
                        </div>
                    <? endif ?>

                    <? if (($item->status) || ($item->price)) : ?>
                        <div><?= $item->status->name ?> <?= $item->price ?></div>
                    <?endif?>

                    <? if ($item->caption): ?>
                        <div><?= $item->caption ?></div>
                    <? endif ?>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
