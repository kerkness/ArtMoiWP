<div class="modal fade" id="myModal-<?= $detail['artmoiObjectId'][0]?>" imgsrc="<?= $image ?>">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h2 class="modal-title"><? if ($detail['title'][0]) : ?><?= $detail['title'][0] ?><? else: ?> Untitled<? endif ?>
                    <? if ($detail['creator'][0]) : ?> by <?= $detail['creator'][0] ?><? endif ?>
                </h2>
            </div>
            <div class="modal-body">

                <img id="img-myModal-<?= $detail['artmoiObjectId'][0]?>"/>

                <div>

                    <? if ($detail['creator'][0]) : ?><h3><?= $detail['creator'][0] ?></h3><? endif ?>
                    <div style="font-style: italic;"><? if ($detail['title'][0]) : ?> <?= $detail['title'][0] ?>  <? else: ?> Untitled<? endif ?> </div>

                    <div><? if ($detail['medium'][0]) : ?>
                            <?= $detail['medium'][0] ?><? endif ?><? if($detail['year'][0] || $detail['month'][0]) : ?>, <?= $detail['month'][0] ?> <?= $detail['year'][0] ?> <? endif ?></div>

                    <? if ($detail['depth'][0] || $detail['width'][0] || $detail['height'][0]) : ?>
                        <div> <?= $detail['width'][0] ?> <? if ($detail['height'][0]) : ?> x <?= $detail['height'][0] ?><? endif ?>
                            <? if ($detail['depth'][0]) : ?> x <?= $detail['depth'][0] ?><? endif ?> <?= $detail['unit'][0] ?></div> <? endif ?>

                    <? if (($detail['status'][0]) || ($detail['price'][0])) : ?>
                        <div><?= $detail['status'][0] ?> <?= $detail['price'][0] ?></div> <?endif?>

                    <? if ($detail['caption'][0]): ?>
                        <div><? print_r($detail['caption'][0]) ?></div>
                    <? endif ?>

                    <? if ($detail['address'][0]) : ?>
                        <div>
                            <h3>
                                <? if ($detail['address'][0]) : ?><?= $detail['address'][0] ?><? endif ?>
                            </h3>
                            <a href="https://www.google.ca/maps/place/<?= $detail['address'][0] ?>">View in Google Maps</a>
                        </div>
                    <? endif ?>

                    <? if ($detail['copyright'][0]) : ?>
                        <div class="text-right"><?= $detail['copyright'][0] ?></div><? endif ?>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
