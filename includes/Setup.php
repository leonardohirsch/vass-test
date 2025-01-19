<?php

namespace VassRickMorty\Includes;

/**
 * Class Setup
 *
 * This class handles the main setup for the Vass Rick and Morty plugin.
 */
class Setup {

    /**
     * Initialize the plugin setup
     */
    public static function run()
    {
        add_action('plugins_loaded', [self::class, 'load_textdomain']);
        add_action('init', [self::class, 'register_custom_post_type']);
        add_action('init', [self::class, 'register_taxonomy']);
        add_action('init', [self::class, 'frontend_init']);
        add_action('admin_init', [self::class, 'add_admin_ajax']);        
        add_action('admin_menu', [self::class, 'add_admin_pages']);
    }

    public static function add_admin_ajax()
    {
       $importer = new CharacterImporter();
       $character_ajax_handler = new \VassRickMorty\Admin\CharacterAjax($importer);
       $character_ajax_handler->init(); 
    }

    public static function add_admin_pages()
    {
        self::admin_import_page(); 
        self::admin_import_settings_page();   
    }

    public static function admin_import_page()
    {        
        $import_page = new \VassRickMorty\Admin\CharacterImportPage();
        $import_page->init();           
    }

    public static function admin_import_settings_page()
    {
        $setting_page = new \VassRickMorty\Admin\ImportSettingPage();
        $setting_page->register_settings();
    }

    public static function frontend_init()
    {        
        $query_handler = new \VassRickMorty\Public\CptQueryHandler();
        $ajax_handler = new \VassRickMorty\Public\CharacterAjax($query_handler);
        $ajax_handler->init();
        $character_shortcode = new \VassRickMorty\Public\CharacterShortcode($query_handler);
        $character_shortcode->execute(); 
    }


    public static function load_textdomain()
    {
        load_plugin_textdomain(RICK_MORTY_TEXT_DOMAIN, false, basename(dirname(__FILE__)) . '/languages');
    }

    public static function register_custom_post_type()
    {
        CharacterCPT::register();
    }

    public static function register_taxonomy()
    {
        $species_taxonomy = new SpeciesTaxonomy();
        $species_taxonomy->register();
    }
}
