<div class="wrap bootstrap-wrapper">
  <h2> ArtMoi Settings</h2>
        <form method="POST" action="options.php" name="settings_form" id="settings_form">
            <table class="table table-striped">
                <tr>
                    <td>
                        <h3>ArtMoi API Key</h3>
                        <? settings_fields('artmoiwp_apikey'); ?>
                        <? do_settings_sections('artmoiwp_apikey'); ?>

                        <p>
                        ArtMoi API Key :
                        <input type="text" name="artmoiwp_apikey" />
                            <? submit_button("save"); ?>
                        </p>
                        <? if( $apiKey ) : ?>
                            <div class="alert alert-success" role="alert">
                                Your API Key <span style="color:red;"> <?= $apiKey ?> </span>has been saved successfully!
                            </div>
                        <? else: ?>
                               <div class="alert alert-danger" role="alert">
                                    <span class="sr-only">Error:</span>
                                    ArtMoi API Key is empty!
                               </div>
                        <? endif ?>
                    </td>
                </tr>

                <tr>
                    <td>
                        <p>
                            <button class="btn btn-info" type="button" onclick='window.open("http://artmoi.me/integrations")'>
                                    Get API Key
                                </button>
                        </p>
                    </td>
                </tr>
            </table>
        </form>
</div>


