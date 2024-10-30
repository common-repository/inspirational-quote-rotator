<?php 
/**
 * Plugin Name: Inspirational Quote Rotator
 * Plugin URI: 
 * Description: If you're looking for the perfect way to inspire your visitors, the Inspirational Quote Rotator is the perfect plugin for you. Just activate and each visitor will see an inspirational quote appear when he visits your website. 
 * Author: Kris Santana
 * Author URI: http://www.nationalcoachacademy.com/
 * Version: 1.0.0
 */
define('IQR_ROOT',__FILE__);

require_once dirname(__FILE__).'/src/iqr-main.php';

IQR_Main::init();