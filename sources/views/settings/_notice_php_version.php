<!-- Error for php version -->
<?php global $wp_version; ?>
<div class="error">
    <p>
        <?php _e( 'grizzly-mg', 'my-text-domain' ); ?>
    </p>
    <p>
        <?php _e("Your php (actual version :".phpversion()." / required : ".REQUIRED_PHP_VERSION.") or wordpress (actual version :".$wp_version." / required : ".REQUIRED_WORDPRESS_VERSION.") version  is lower than requirement. Please update and try again!")?>
    </p>
    <p style="text-align: center">
        <button class="button action" onclick="window.history.go(-1);">Back</button>
    </p>
</div>