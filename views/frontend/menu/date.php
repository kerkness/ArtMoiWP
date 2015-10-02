<div class="well">
    <?foreach($dates as $date) : ?>
        <a href="<?= esc_url( add_query_arg( 'daterange',  str_replace(' ', '', $date)  ) ) ?>" style="margin-left:1em;"><? echo strtoupper($date) ?></a>
    <?endforeach?>
</div>
