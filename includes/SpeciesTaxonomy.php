<?php

namespace VassRickMorty\Includes;

/**
 * Class SpeciesTaxonomy
 *
 * This class handles the taxonomy for species in the Rick and Morty plugin.
 */
class SpeciesTaxonomy extends TaxonomyBase {
    /**
     * SpeciesTaxonomy constructor.
     */
    public function __construct()
    {
        $taxonomy_name = RICK_MORTY_PREFIX . 'species';
        $object_type = ['character'];
        $args = [
            'labels' => [
                'name' => __('Species', RICK_MORTY_TEXT_DOMAIN),
                'singular_name' => __('Species', RICK_MORTY_TEXT_DOMAIN)
            ],
            'public' => true,
            'hierarchical' => true,
            'show_ui' => false,
            'show_admin_column' => true,
            'show_in_nav_menus' => false,
            'show_in_rest' => true
        ];

        parent::__construct($taxonomy_name, $object_type, $args);
    }
}
