<? if ($items) : ?>
    <!-- ITEMS LOADED FROM LIVE API CALL -->
    <? foreach ($items as $item)  : ?>

        <div class="modal fade" id="myModal-<?= $item->objectId ?>" imgsrc="<?= $item->imageUrl() ?>">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h2 class="modal-title"><? if ($item->title) : ?><?= $item->title ?><? else: ?> Untitled<? endif ?>
                        </h2>
                    </div>
                    <div class="modal-body">

                        <img id="img-myModal-<?= $item->objectId ?>"/>

                        <? Flight::view()->render('frontend/template/listing', array('item' => $item)) ?>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>


            </div>
        </div>


    <? endforeach ?>


<? endif ?>


<script>
    jQuery(function ($) {
        $('#myModal').modal();
        $('.modal').on('shown.bs.modal', function (e) {
            var imgsrc = $(this).attr('imgsrc');
            $('#img-' + $(this).attr('id')).attr('src', imgsrc);
            console.log(imgsrc);
        })
    });
</script>


