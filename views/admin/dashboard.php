<div class="wrap bootstrap-wrapper">
<h2>ArtMoi Plugin Dashboard</h2>
    <form method="post" action="" name="dashboard_form" id="dashboard_form">
    <h3>Select a Report</h3>
    <select name="dashboard-report">
    <option value="">...</option>
    <? foreach( $reports as $report ) : ?>
        <option value="<?= $report->name ?>"><?= $report->name ?></option>
    <? endforeach ?>
    </select>

    <?= $grid ?>


        <? submit_button("Buttttton"); ?>
    </form>
</div>