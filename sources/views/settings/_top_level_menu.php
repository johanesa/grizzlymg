<div class="wrap">
    <h1><?= esc_html(get_admin_page_title()); ?></h1>
    <form action="options.php" method="post">
        <?php
        // output save settings button
        submit_button('Save custom settings');
        ?>
    </form>
</div>