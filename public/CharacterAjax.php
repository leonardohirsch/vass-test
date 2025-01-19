<?php

namespace VassRickMorty\Public;

/**
 * Class CharacterAjax
 *
 * Handles AJAX requests for loading Rick and Morty Characters Custom Post Type.
 */
class CharacterAjax extends CptAjaxHandlerBase {

    /**
     * Add Ajax hooks.
     * 
     * @return void
     */
    public function init() : void
    {
        add_action('wp_ajax_load_characters', [$this, 'handle_loading']);
        add_action('wp_ajax_nopriv_load_characters', [$this, 'handle_loading']);
    }

    /**
     * Handle the loading of Characters Custom Post Type via AJAX requests.
     * 
     * @return void
     */
    public function handle_loading() : void
    {
        if (!check_ajax_referer('load_rick_morty_nonce')) {
            wp_send_json_error(['message' => __('Unauthorized request.', RICK_MORTY_TEXT_DOMAIN)]);
        }
        $name = sanitize_text_field($_POST['name'] ?? '');
        // $meta_query = !empty($name) ? [['key' => 'name', 'value' => $name, 'compare' => 'LIKE']] : [];
        $species = sanitize_text_field($_POST['species'] ?? '');
        $tax_query = !empty($species) ? [['taxonomy' => RICK_MORTY_PREFIX . 'species', 'field' => 'name', 'terms' => $species]] : [];
        $page = intval(sanitize_text_field($_POST['page']) ?? 1);

        $posts = $this->queryHandler->get_cpt_posts(
            RICK_MORTY_PREFIX . 'character',
            $page,
            $name,
            [],
            $tax_query
        );
        
        if (empty($posts)) {
            wp_send_json_error(['message' => __('No characters found.', RICK_MORTY_TEXT_DOMAIN)]);
        } else {
            wp_send_json(['posts' => $posts]);
        }
    }
}
