<?php

namespace VassRickMorty\Includes;

abstract class ImporterBase {
    protected static string $post_type = '';

    public function __construct(
        protected string $api_url
    ) {}

    public function import_data()
    {
        $endpoint_url = $this->ensure_api_url_format();

        $cache_count_name = self::$post_type . '_count';
        $item_count = get_transient($cache_count_name);
        if ($item_count === false) {
            $item_count = $this->count_posts();
        }

        $page_to_fetch = intdiv($item_count, 20) + 1;
        $data = $this->fetch_page_from_api($endpoint_url, $page_to_fetch);
        if ($data === null || !is_array($data)) return;

        if ($item_count < $data['info']['count']) {
            $this->process_api_data($data['results']);

            $new_posts = $this->count_posts($item_count);
            $this->update_cache_item_count($cache_count_name, $item_count + $new_posts);
        }
    }    

    protected function items_exists(string|int $api_id)
    {
        $query = new \WP_Query([
            'post_type'      => self::$post_type,
            'meta_key'       => 'id',
            'meta_value'     => $api_id,
            'posts_per_page' => 1
        ]);

        return $query->have_posts();
    }

    protected function count_posts(int $offset = 0)
    {
        $query = new \WP_Query([
            'post_type' => self::$post_type,
            'posts_per_page' => -1,
            'fields' => 'ids',
            'offset' => $offset
        ]);

        return $query->found_posts;
    }

    protected function ensure_api_url_format(): string
    {
        $url = $this->api_url;
        if (substr($url, -1) !== '/') {
            $url .= '/';
        }
        return $url;
    }

    protected function fetch_page_from_api(string $endpoint_url, string|int $page): ?array
    {
        $response = wp_remote_get($endpoint_url . '?page=' . $page);
        if (is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200) {
            error_log('Error fetching data: ' . wp_remote_retrieve_response_message($response));
            return null;
        }

        $data = json_decode(wp_remote_retrieve_body($response), true);
        if (!isset($data['results']) || !isset($data['info'])) {
            error_log('Invalid API response');
            return null;
        }
        return $data;
    }

    protected function process_api_data(array $items)
    {
        foreach ($items as $item) {
            $this->process_item($item);
        }
    }

    protected function update_cache_item_count(string $cache_count_name, int $new_count)
    {
        $transient_duration = get_option(self::$post_type . '_transient_duration', 30 * DAY_IN_SECONDS);
        set_transient($cache_count_name, $new_count, $transient_duration);
    }

    abstract protected function process_item($item);
}
