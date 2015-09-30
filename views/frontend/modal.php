<? if ($details) : ?>
<!-- DETAILS LOADED FROM SYNCED WORDPRESS POSTS -->
<? for ($i = $total-1; $i >= 0; $i--)  : ?>
    <? Flight::view()->render('frontend/synced/modal', array('detail' => $details[$i], 'image' => $images[$i])) ?>
<? endfor ?>
<? endif ?>

<? if ($items) : ?>
    <!-- ITEMS LOADED FROM LIVE API CALL -->
    <? foreach ($items as $item)  : ?>
        <? Flight::view()->render('frontend/item/modal', array('item' => $item)) ?>
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


