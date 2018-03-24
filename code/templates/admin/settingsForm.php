<h2><?=$this->e($title)?></h2>
<form action="<?=$this->e($action)?>" method="post">
<?php /* 
Wordpress wasn't really setup with template engines in mind.
So we're calling global WP methods in our template. Typically
these would be in the admin class where front-end / view logic
and backend logic get all spaghetti'd

Since we need to call these wordpress methods we're forced to use
a template engine that has access to the full php global scope.
Instead of using some "logic-less" sandboxed template engine.
But this is wordpress. So terrible code I guess should be expected. :/
            --nathan
*/ ?>
    <?php settings_fields($option_name); ?>
    <?php do_settings_sections($id); ?>
    <?php submit_button(); ?>
</form>
