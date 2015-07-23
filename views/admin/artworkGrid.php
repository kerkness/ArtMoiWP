<h3><?= $gridTitle ?></h3>

    <div id="thumbnails" class="row">

        <? foreach($artwork as $art) : ?>
            <div class="col-sm-2">
                <a href="#" class="thumbnail">
                    <img class="img-responsive" src="<?= $art->images[0]->imageFileThumbnail ?>">
                </a>


            </div>
        <? endforeach ?>

    </div>

    <pre>
                        <? print_r($art) ?>
    </pre>

    <!--

    <div class="row">
        <div class="col-md-12">
            <a id="loadMore" class="btn btn-default" href="#">More</a>
            <img class="loadingGif btn btn-default" id="loadingGif" src="/assets/icons/loading.gif" style="display:none"/>
            <p>&nbsp;</p>
        </div>
    </div>

    -->
