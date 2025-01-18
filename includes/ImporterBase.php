<?php

namespace VassRickMorty\Includes;

abstract class ImporterBase {
    public function __construct(
        protected string $api_url
    ) {}
    
    public function import_data()
    {
        $page = 1;
        do {
            $response = wp_remote_get($this->api_url . '?page=' . $page);
            if (is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200) {
                error_log('Error fetching data: ' . wp_remote_retrieve_response_message($response));
                return;
            }

            $data = json_decode(wp_remote_retrieve_body($response), true);
            if (!isset($data['results'])) {
                error_log('Invalid API response');
                return;
            }

            foreach ($data['results'] as $item) {
                $this->process_item($item);
            }

            $page = isset($data['info']['next']) ? ++$page : null;
        } while ($page);
    }

    protected function items_exists(string $post_type, string|int $api_id)
    {
        $query = new \WP_Query([
            'post_type'      => $post_type,
            'meta_key'       => 'id',
            'meta_value'     => $api_id,
            'posts_per_page' => 1
        ]);

        return $query->have_posts();
    }

    abstract protected function process_item($item);
}
