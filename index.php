<?php
    /**
     * @package WordPress
     * @subpackage RW_theme
     */

    /* Required modules */
    require('SitesUtil.php');
    SitesUtil::addIncludePath('/var/web-controllers/php_modules');
    require('jsontemplate.php');

    /* Get wordpress data as array */
    $wordpressData = SitesUtil::getWordpressData();

    print_r($wordpressData);
?>

