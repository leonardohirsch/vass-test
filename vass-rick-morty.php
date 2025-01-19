<?php
/**
 * Plugin Name: Rick and Morty
 * Description: A plugin to manage characters from The Rick and Morty API.
 * Version: 1.0
 * Author: Vass
 */

 // Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;    
}

define('RICK_MORTY_TEXT_DOMAIN', 'vass-rick-morty');
define('RICK_MORTY_PREFIX', 'vass-rm-');

require_once plugin_dir_path(__FILE__) . 'vendor/autoload.php';

VassRickMorty\Includes\Setup::run();
