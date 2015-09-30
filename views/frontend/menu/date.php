<div class="well">
    <?foreach($dates as $date) : ?>
        <a href="<?=$link?>?date=<?=$date?>" style="margin-left:1em;"><? echo strtoupper($date) ?></a>
    <?endforeach?>
</div>
