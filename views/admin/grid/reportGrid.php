
<div id="thumbnails" class="row">
<? if (!($results)): ?>
    <div class="alert alert-warning" role="alert">Item not Found</div>
<? else : ?>

    <? foreach( $results as $result) : ?>
        <div class="col-sm-2">
            <a href="#" class="thumbnail">
                <img class="img-responsive" src="<?= $result->images[0]->imageFileThumbnail ?>">
                <input type="hidden" name="objectData" class="objectData" value="<?= htmlspecialchars(json_encode($result))?>" >
            </a>
        </div>
    <? endforeach ?>

<? endif ?>
</div>