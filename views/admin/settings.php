
<div class="wrap">
  <h2> Artmoi WP Settings</h2>


    <!--<form method="post" action="options.php" name="settings_form" id="settings_form">-->
    <form method="post" name="settings_form" id="settings_form">
       ArtMoi API Key: <input type="text" name="apiKey" id="apiKey"><br/>
       <!-- Add "Where do I find my API Key?" -->
      <input type="submit" class="submit-button" value="Save Changes" name="clear" />
    </form>


    <? if( $apiKey ) : ?>
        <h2>You're API Key</h2>
        <p><?= $apiKey ?></p>
    <? endif ?>

</div>

