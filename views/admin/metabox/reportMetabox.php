
<!--
 ========
 Report Meta Box
 ========
 -->

<? if($pageType == "report") : ?>




    <? if ($reports) : ?>
        <? foreach ( $reports as $report ) :  ?>
            <input type="radio" name="listSelected" id="reportSelected" value="<?="report.".$report->objectId ?>"
                   <? if($reportChecked == $report->objectId) : ?>checked="checked"<? endif; ?>>
            <?=$report->name?>
            <? if($reportChecked == $report->objectId) : ?>
                <span style="color:#E76767;"> -> this report is linked</span>
                <input type="radio" name="listSelected" id="reportSelected" value="<?= "delete.report.".$report->objectId ?>"> Unlink
            <? endif; ?>
            <br/>
        <? endforeach ?>
    <? else : ?>
        no synced reports...
    <? endif ?>
<? endif ?>

