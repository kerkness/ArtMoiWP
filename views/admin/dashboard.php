<div class="wrap bootstrap-wrapper">
<h2>ArtMoi Plugin Dashboard</h2>

    <h3>Select a Report</h3>

    <? foreach( $reports as $report ) : ?>
        <p><?= $report->name ?></p>
    <? endforeach ?>

    <?= $grid ?>



</div>