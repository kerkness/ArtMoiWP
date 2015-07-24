<h3><?= $gridTitle ?></h3>
    <div id="thumbnails" class="row">

            <? foreach($artwork as $art) : ?>
                <div class="omitem col-sm-2">
                    <a href="#" class="thumbnail" objectId="<?= $art->objectId ?>">
                        <img class="img-responsive" src="<?= $art->images[0]->imageFileThumbnail ?>">
                        <input id="<?= $art->objectId ?>" type="hidden" class="objectSelected" value="">
                    </a>
                </div>
            <? endforeach ?>

   </div>




<div class="row">
    <div class="col-md-12">
        <a id="loadMore" class="btn btn-default" href="#">More</a>
        <img class="loadingGif btn btn-default" id="loadingGif" src="images/loading.gif" style="display:none"/>
        <input type="hidden" id="hiddenKey" value="<?= $apiKey ?>" ?>
        <p>&nbsp;</p>
    </div>
</div>




<pre>
        <? print_r($art) ?>
</pre>
