<!--
============================
Customize your own template
============================

For item details:
 $details[posIndexNumber]["infoname"][0]

* infoname list:
title, caption, medium, address,
year, month, creator, tag,
width, height, depth, unit,
price, copyright, status, artmoiObjectId

* artmoiObjectId is a unique image ID
* Example (Delete the space between < and?) : <span> < ? $details[1]["creator"][0] ?> </span>

-->



<? if ($details) : ?>
    <? for ($i = $total-1; $i >= 0; $i--)  : ?>

        <div class="col-xs-6 col-md-3">
            <div class="thumbnail">
                <a data-toggle="modal" data-target="#myModal-<?= $details[$i]['artmoiObjectId'][0] ?>">
                    <img src="<?= $thumbnailImages[$i] ?>">
                </a>
            </div>
        </div>

    <?endfor?>
<?endif?>

<?=$modal?>


