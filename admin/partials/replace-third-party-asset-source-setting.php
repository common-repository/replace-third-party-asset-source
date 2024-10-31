<?php

/**
 * Setting Page
 */
if ( ! defined( 'WPINC' ) ) {
    die();
}
$option_group = $this->prefix . 'group';

?>

<div class="wrap">
    <h1>Replace Asset Source Setting Page</h1>
    <div class="card rtpas_help">
        <h4 class="woocommerce-card__header">Instructions and notes</h4>
        <li>Use "Activate Asset Replacement" box to activate or deactivate the functionality.</li>
        <li>Start to insert the target source script, and the replacement of script url.</li>
        <li><u><strong>DO NOT</strong></u> insert the parameters in both Target Aseet URL and Replacement Asset URL.</li>
        <li>Just put target asset url as <strong>https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css</strong>  if you found the target asset url through browser developer tool is <strong>https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css?ver=2.0.9</strong></li>
        <li>Remove any replacement if you want to use back the original asset source from the plugin or theme.</li>
        <li>Click "Save changes" after every adjustment.</li>
        <li>Clear your cache if you are using any cache pugin.</li>
        <li>Each replacement must contains of Target Asset URL and Replacement Asset URL, nothing will happen if either one is empty.</li>
        <li>There will be no validation on your URL, if it's an invalid url, you will be getting 404 error when the asset loaded.</li>
        <li>If the plugin/theme developer not using standard WordPress enqueue method to load the target asset file, the replacement will NOT works.</li>
        <li>Can drop email to <a href="mailto:me@jenn.support">Jenn</a> if any bug found.</li>
    </div>
    <form action="options.php" method="post">
    <?php

        settings_fields( $option_group );

        do_settings_sections( $option_group );

        submit_button();
    ?>
    </form>
</div>