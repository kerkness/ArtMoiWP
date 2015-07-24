
<div class="wrap">
  <h2> Artmoi WP Settings</h2>


    <form method="post" action="options.php" name="settings_form" id="settings_form">
    <table>
     <tr>
         <td>
             <h3>ArtMoi API Key</h3>

       <!-- TODO: Add "Where do I find my API Key? instruction" -->
             <a href="http://omona.me/integrations" target="_blank">Find an API Key for your Wordpress Plugin</a> <br/>

        <? settings_fields('artmoiwp_apikey'); ?>
        <? do_settings_sections('artmoiwp_apikey'); ?>
        ArtMoi API Key:
        <input type="text" name="artmoiwp_apikey" value="<? get_option('artmoiwp_apikey',''); ?>" />
        <? submit_button("Save"); ?>
        <? if( $apiKey ) : ?>
            Your API Key <span style="color:red;"> <?= $apiKey ?> </span>has been saved succesfully!
        <? else: ?>
            Please enter a valid API key
        <? endif ?>
         </td>
     </tr>
    </table>
</form>




</div>

