<div>
    <select name="templateSelected">
        <option value="...">Select...</option>

        <?foreach($templateType as $template) : ?>
            <option value="<?=$template?>" <? if($templateSelected == $template) : ?>selected<?endif?> > <?=$template?></option>
        <?endforeach?>

    </select>
</div>

