<!--
=========
ALL ITEMS
=========
-->
<!---->
<!--<div class="wrap bootstrap-wrapper">-->
<!--    <h3> All Items </h3>-->
<!---->
<!--    <table class="table table-striped">-->
<!--        <tr>-->
<!--            <td style="vertical-align:middle"> All Items </td>-->
<!---->
<!--            <td class="text-center" style="vertical-align:middle">-->
<!---->
<!--                --><?// if($syncedAllItems->timestamp) : ?>
<!--                    <span style="font-weight:bold;">-->
<!--                        This report has been synced on --><?//= $syncedAllItems->timestamp ?>
<!--                    </span>-->
<!--                --><?// endif ?>
<!--            </td>-->
<!---->
<!--            <td class="text-right">-->
<!--                <a class="btn btn-default" href="admin.php?page=artmoi-view-items&pageType=all&listId=allItems&listTitle=All Items">View Items</a>-->
<!--            </td>-->
<!--        </tr>-->
<!---->
<!--    </table>-->
<!---->
<!--</div>-->



<!--
 ========
 Reports
 ========
 -->

<div class="wrap bootstrap-wrapper">

    <h3> ArtMoi Reports </h3>
    <p> Max 100 Reports</p>

    <table class="table table-striped">
        <? if($reports) : ?>
            <? foreach( $reports as $report ) : ?>
                <tr>
                    <td style="vertical-align:middle">
                        <? if ( (strlen($report->name) < 20) ) : ?>
                            <?=$report->name?>
                        <? else : ?>
                            <? echo substr($report->name,0,20)?>...
                        <?endif?>
                    </td>

                    <td class="text-center" style="vertical-align:middle">
                    <? if($syncedReports) : ?>
                            <? foreach($syncedReports as $syncedReport) : ?>
                                <? if($report->objectId == $syncedReport->reportId) : ?>
                                    <span style="font-weight:bold;">
                                              This report has been synced on <?= $syncedReport->timestamp ?>
                                         </span>
                                <?endif ?>
                            <? endforeach?>
                    <? endif ?>
                    </td>

                    <td class="text-right">
                        <a class="btn btn-default" href="admin.php?page=artmoi-view-items&pageType=report&listId=<?= $report->objectId ?>&listTitle=<?= $report->name ?>">View Report</a>
                    </td>
                </tr>
            <? endforeach ?>
        <? else : ?>
            <tr>
                <td> <div class="alert alert-warning" role="alert"> Report not found.. </div> </td>
            </tr>
        <? endif; ?>
    </table>
</div>


<!--
 ========
 Collections
 ========
 -->
<div class="wrap bootstrap-wrapper">

    <h3> ArtMoi Collections </h3>
    <p>Max 100 collections </p>

    <table class="table table-striped">
        <? if($collections) : ?>
            <? foreach( $collections as $collection ) : ?>
                <tr>
                    <td style="vertical-align:middle">
                        <? if ( (strlen($collection->name) < 20) ) : ?>
                        <?=$collection->name?>
                        <? else : ?>
                        <? echo substr($collection->name,0,20)?>...
                        <?endif?>
                    </td>

                    <td class="text-center" style="vertical-align:middle">
                        <? if($syncedCollections) : ?>
                            <? foreach($syncedCollections as $syncedCollection) : ?>
                                <? if($collection->publicId == $syncedCollection->collectionId) : ?>
                                     <span style="font-weight:bold;">
                                          This collection has been synced on <?= $syncedCollection->timestamp ?>
                                     </span>
                                <?endif ?>
                            <? endforeach?>
                        <? endif ?>
                    </td>

                    <td class="text-right">
                        <a class="btn btn-default" href="admin.php?page=artmoi-view-items&pageType=collection&listId=<?= $collection->publicId ?>&listTitle=<?= $collection->name ?>">View Collection</a>
                    </td>
                </tr>
            <? endforeach ?>
        <? else : ?>
            <tr>
                <td> <div class="alert alert-warning" role="alert"> Collection not found.. </div> </td>
            </tr>
        <? endif; ?>
    </table>
</div>
