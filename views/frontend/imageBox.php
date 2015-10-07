<?if($item->images && (count($item->images) > 1)):?>
    <div class="row margin-top-sm text-center">
    <?foreach($item->images as $image) : ?>
        <a href="<?=$image->imageFileSized?>" class="thumbnailImage">
            <div class="col-md-3 col-xs-4 margin-top-sm">
                <img src="<?=$image->imageFileThumbnail?>" class="center-block">
            </div>
        </a>
    <?endforeach?>
    </div>
<?endif?>


<script>
    +function ($) {
        $(document).ready(function(){
            $(".thumbnailImage").click(function(){
                $('.mainImage').hide();
                $('.imageWrap').css('background-image',"url('../../images/loading.gif')");

                var i=$('<img/>').attr('src',this.href).load(function(){
                    $('.mainImage').attr('src', i.attr('src'));
                    $('.imageWrap').css('background-image', 'none');
                    $('.mainImage').fadeIn();
                });
                return false;
            });
        });
    }(jQuery);

</script>