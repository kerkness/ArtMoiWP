
<div id="thumbnails" class="row">
    <? if (( !($results)) || !($pageType)) : ?>
        <div class="alert alert-warning" role="alert">Item not Found</div>

    <? else :?>

        <? if (($pageType == "report") || ($pageType == "all")) : ?>
            <? foreach( $results as $result) : ?>
                <div class="omitem col-sm-2">
                    <a href="#" class="thumbnail">
                        <img class="img-responsive" src="<?= $result->images[0]->imageFileThumbnail ?>">
                        <input type="hidden" name="objectData" class="objectData" value="<?= htmlspecialchars(json_encode($result))?>" >
                        <?if($pageType == "all") : ?>
                            <div class="caption"><?=substr($result->title,0,15)?></div>
                        <?endif?>
                    </a>
                </div>
            <? endforeach ?>

        <? elseif ($pageType == "collection") : ?>
            <?if($results->items) : ?>
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
            <?endif?>

        <? endif ?>
    <? endif ?>
</div>



