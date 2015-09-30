<div class="well">
    <?foreach(range($atts['range_start'], $atts['range_end']) as $letter) : ?>
        <a href="#<?=$letter?>" style="margin-left:1em;"><? echo strtoupper($letter) ?></a>
    <?endforeach?>
</div>
