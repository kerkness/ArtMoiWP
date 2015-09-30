<div id="thumbnails" class="row">

    <? if (!($results)): ?>

        <div class="alert alert-warning" role="alert">Item not Found</div>

    <? else : ?>

        <?if($results->items) : ?>
            <? if($results->privacy->publicImages == 1) : ?>
            <? foreach ($results->items as $result) : ?>
                <? if(!$result->isPrivate) : ?> <!-- Do not display private items -->
                    <div class="omitem col-sm-2">
                        <a href="#" class="thumbnail">
                            <img class="img-responsive" src="<?= $result->images[0]->imageFileThumbnail ?>" >
                            <input type="hidden" name="objectData" class="objectData" value="<?= htmlspecialchars(json_encode($result)) ?> ">
                        </a>
                    </div>
                <? endif ?>
            <? endforeach ?>

            <? else : ?>
                <div class="alert alert-warning col-md-3" role="alert">
                   Images are NOT public
                    <br/>
                   Please check your Collection Privacy Settings.
                </div>
            <? endif ?>
        <?endif?>

    <? endif ?>
</div>
