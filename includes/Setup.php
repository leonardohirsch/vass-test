<?php

namespace VassRickMorty\Includes;

/**
 * Class Setup
 *
 * This class handles the main setup for the Vass Rick and Morty plugin.
 */
class Setup {

    /**
     * Initialize the setup by registering custom post types and taxonomies.
     */
    public static function run()
    {
        add_action('plugins_loaded', [self::class, 'load_textdomain']);
        add_action('init', [self::class, 'register_custom_post_type']);
        add_action('init', [self::class, 'register_taxonomy']);
        add_action('admin_menu', [self::class, 'add_admin_pages']);
    }

    public static function add_admin_pages()
    {
        self::admin_import_page(); 
        self::admin_settings_page();   
    }

    public static function admin_import_page()
    {
        new VassRickMorty\Admin\CharacterImportPage(
            new VassRickMorty\Includes\CharacterImporter()
        );            
    }

    public static function admin_settings_page()
    {

    }


    public static function load_textdomain()
    {
        load_plugin_textdomain(RICK_MORTY_TEXT_DOMAIN, false, basename(dirname(__FILE__)) . '/languages');
    }

    public static function register_custom_post_type()
    {
        new VassRickMorty\Includes\CharacterCPT();
    }

    public static function register_taxonomy()
    {
        new VassRickMorty\Includes\SpeciesTaxonomy();
    }
}
