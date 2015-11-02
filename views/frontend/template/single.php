
<? if ($item) : ?>
    <div class="clearfix"></div>
    <h2><?if($item->title) : ?><?=$item->title?><? else : ?>Untitled<?endif?></h2>
    <div class="col-md-5 col-xs-12">
        <? if($item->imageUrl()) : ?>
        <div class="imageWrap center-block row">
            <img src="<?=$item->imageUrl()?>" class="mainImage img-responsive">
        </div>
        <?endif?>
    </div>
    <div class="col-md-6 col-xs-12">
        <? Flight::view()->render('frontend/imageBox', array('item' => $item)) ?>
        <? Flight::view()->render('frontend/template/listing', array('item' => $item)) ?>
    </div>
<?endif?>

<div class="clearfix"></div>


<script>
    +function ($) {
        $(document).ready(function(){
            $('.entry-title').css('display',"none");
        });
    }(jQuery);
</script>
