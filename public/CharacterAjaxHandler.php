<?php

namespace VassRickMorty\Public;

use VassRickMorty\Public\EntityAjaxHandlerBase;

class CharacterAjaxHandler extends EntityAjaxHandlerBase {

    public function execute()
    {
        add_action('wp_ajax_load_characters', [$this, 'handleLoading']);
        add_action('wp_ajax_nopriv_load_characters', [$this, 'handleLoading']);
    }

    public function handleLoading() {
        if (check_ajax_referer('load_rick_morty_nonce')) {
            wp_send_json_error(['message' => __('Unauthorized request.', RICK_MORTY_TEXT_DOMAIN)]);
        }
        $name = sanitize_text_field($_POST['name'] ?? '');
        $meta_query = !empty($name) ? [['key' => 'name', 'value' => $name, 'compare' => 'LIKE']] : [];
        $species = sanitize_text_field($_POST['species'] ?? '');
        $tax_query = !empty($species) ? [['taxonomy' => 'species', 'field' => 'name', 'terms' => $species]] : [];
        $page = intval(sanitize_text_field($_POST['page']) ?? 1);

        $posts = $this->queryHandler->fetchEntity(
            RICK_MORTY_PREFIX . 'character',
            $page,
            $meta_query,
            $tax_query
        );
        
        if (empty($posts)) {
            wp_send_json_error(['message' => __('No characters found.', RICK_MORTY_TEXT_DOMAIN)]);
        } else {
            wp_send_json(['posts' => $posts]);
        }
    }
}
