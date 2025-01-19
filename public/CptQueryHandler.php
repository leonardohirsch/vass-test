<?php

namespace VassRickMorty\Public;

/**
 * Class CptQueryHandler
 *
 * Handles CPT queries for the Rick and Morty plugin.
 */
class CptQueryHandler {

    /**
     * Callback for remove_filter in order to add post title search filter.
     *
     * @param string $where The WHERE clause of the query.
     * @param \WP_Query $query The WP_Query instance.
     * 
     * @return string The modified WHERE clause.
     */
    public function add_post_title_search($where, $query)
    {
        global $wpdb;
        if ($title = $query->get('search_title')) {
            $where .= $wpdb->prepare(" AND " . $wpdb->posts . ".post_title LIKE %s", '%' . $wpdb->esc_like($title) . '%');
        }
        return $where;
    }

    /**
     * Get CPT posts.
     *
     * @param string $post_type The post type to query.
     * @param int $page The page number for pagination.
     * @param string $search_title The title to search for.
     * @param array $meta_query Meta query parameters.
     * @param array $taxonomy_query Tax query parameters.
     * 
     * @return array The list of posts.
     */
    public function get_cpt_posts(
        string $post_type, 
        int $page = 1,
        string $search_title = '',
        array $meta_query = [],
        array $taxonomy_query = []
    ) :  array
    {
        if (!empty($search_title)) {
            add_filter('posts_where', [$this, 'add_post_title_search'], 10, 2);
        }
        
        $args = [
            'post_type' => $post_type,
            'posts_per_page' => 20,
            'paged' => $page,
        ];
        if (!empty($meta_query)) {
             $args['meta_query'] = $meta_query;
        }
        if (!empty($taxonomy_query)) {
             $args['tax_query'] = $taxonomy_query;
        }
        if (!empty($search_title)) {
            $args['search_title'] = trim($search_title);
        }
        $query = new \WP_Query($args);
        $posts = [];
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $post_id = get_the_ID();
                $posts[] = [
                    'title' => get_the_title(),
                    'meta' => $this->get_post_meta_data($post_id)
                ];
            }
        }

        if (!empty($search_title)) {
            remove_filter('posts_where', [$this, 'add_post_title_search'], 10);
        }

        return $posts;
    }

    /**
     * Get taxonomy terms.
     *
     * @param string $taxonomy The taxonomy to get terms for.
     * @return array The list of taxonomy terms.
     */
    public function get_taxonomy_terms(string $taxonomy) : array
    {
        $terms = get_terms(
            ['taxonomy' => RICK_MORTY_PREFIX . $taxonomy,
            'hide_empty' => false]
        );
        return array_map(fn($term) => $term->name, $terms);
    }
        
    /**
     * Get post meta data.
     *
     * @param int $post_id The post ID.
     * 
     * @return array The post meta data.
     */ 
    private function get_post_meta_data( int $post_id ) : array
     {
        $metaData = get_post_meta($post_id);
        $outputMeta = [];
        foreach ($metaData as $key => $value) {
            $outputMeta[$key] = maybe_unserialize($value[0]);
        }
        return $outputMeta;
    }
}
