<?php

namespace VassRickMorty\Public;

class EntityQueryHandler {
    public function getTaxOptions(string $taxonomy) {
        $terms = get_terms(['taxonomy' => RICK_MORTY_PREFIX . $taxonomy, 'hide_empty' => false]);
        return array_map(fn($term) => $term->name, $terms);
    }

    public function fetchEntity(
        string $postType, 
        int $page = 1,
        string $searchTitle = '',
        array $metaQuery = [],
        array $taxQuery = []
    )
    {
        if (!empty($searchTitle)) {
            add_filter('posts_where', [$this, 'add_post_title_search'], 10, 2);
        }
        
        $args = [
            'post_type' => $postType,
            'posts_per_page' => 20,
            'paged' => $page,
        ];
        if (!empty($metaQuery)) {
             $args['meta_query'] = $metaQuery;
        }
        if (!empty($taxQuery)) {
             $args['tax_query'] = $taxQuery;
        }
        if (!empty($searchTitle)) {
            $args['search_title'] = $searchTitle;
        }
        $query = new \WP_Query($args);
        $posts = [];
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $post_id = get_the_ID();
                $posts[] = [
                    'title' => get_the_title(),
                    'meta' => $this->getPostMeta($post_id)
                ];
            }
        }

        if (!empty($searchTitle)) {
            remove_filter('posts_where', [$this, 'add_post_title_search'], 10);
        }

        return $posts;
    }

    public function add_post_title_search($where, $query) {
        global $wpdb;
        if ($title = $query->get('search_title')) {
            $where .= $wpdb->prepare(" AND " . $wpdb->posts . ".post_title LIKE %s", '%' . $wpdb->esc_like($title) . '%');
        }
        return $where;
    }


     private function getPostMeta($post_id): array
     {
        $metaData = get_post_meta($post_id);
        $outputMeta = [];
        foreach ($metaData as $key => $value) {
            $outputMeta[$key] = maybe_unserialize($value[0]);
        }
        return $outputMeta;
    }
}
