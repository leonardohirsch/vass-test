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
        add_action('init', [self::class, 'frontend_init']);
        add_action('admin_menu', [self::class, 'add_admin_pages']);
    }

    public static function add_admin_pages()
    {
        self::admin_import_page(); 
        self::admin_import_settings_page();   
    }

    public static function admin_import_page()
    {

        $importer = new CharacterImporter();
        $importPage = new \VassRickMorty\Admin\CharacterImportPage($importer);
        $importPage->init();          
    }

    public static function admin_import_settings_page()
    {
        error_log('admin_import_settings_page');
        $setting_page = new \VassRickMorty\Admin\ImportSettingPage();
        $setting_page->register_settings();

    }

    public static function frontend_init()
    {
        if (!is_admin()) {
            $query_handler = new \VassRickMorty\Public\EntityQueryHandler();
            $character_shortcode = new \VassRickMorty\Public\CharacterShortcode($query_handler);
            $character_shortcode->execute();
            $ajax_handler = new \VassRickMorty\Public\CharacterAjaxHandler($query_handler);
            $ajax_handler->execute();       
        }        
    }


    public static function load_textdomain()
    {
        load_plugin_textdomain(RICK_MORTY_TEXT_DOMAIN, false, basename(dirname(__FILE__)) . '/languages');
    }

    public static function register_custom_post_type()
    {
        new CharacterCPT();
    }

    public static function register_taxonomy()
    {
        new SpeciesTaxonomy();
    }
}
