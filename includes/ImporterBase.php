<?php

namespace VassRickMorty\Includes;

/**
 * Base class for importing data from an API.
 */
abstract class ImporterBase {
    protected static string $post_type = '';

    /**
     * Constructor for the ImporterBase class.
     *
     * @param string $api_url The API URL to fetch data from.
     */
    public function __construct(
        protected string $api_url
    ) {

    }

    /**
     * Imports data from the API.
     *
     * @return array The result of the import operation.
     */
    public function import_data(): array
    {
        $endpoint_url = $this->ensure_api_url_format();

        $cache_count_name = static::$post_type . '_count';
        $item_count = get_transient($cache_count_name);
        if ($item_count === false) {
            $item_count = $this->count_posts_in_database();
        }
        
        $page_to_fetch = intdiv($item_count, 20) + 1;
        $data = $this->fetch_page_from_api($endpoint_url, $page_to_fetch);
        if (isset($data['fetch_error'])) {
            return [
                'error' => true,
                'message' => __('Failed to import characters', RICK_MORTY_TEXT_DOMAIN) . ': '. $data['fetch_error']
            ];
        } 

        if ($item_count < $data['info']['count']) {
            $this->process_api_data($data['results']);

            $new_posts = $this->count_posts_in_database($item_count);
            $this->update_cache_item_count($cache_count_name, $item_count + $new_posts);
        }

        return ['success' => true];
    }    

    /**
     * Checks if an item exists based on the API ID.
     *
     * @param string|int $api_id The API ID of the item.
     * 
     * @return bool True if the item exists, false otherwise.
     */
    protected function items_exists(string|int $api_id) : bool
    {
        $query = new \WP_Query([
            'post_type'      => static::$post_type,
            'meta_key'       => 'id',
            'meta_value'     => $api_id,
            'posts_per_page' => 1
        ]);

        return $query->have_posts();
    }

    /**
     * Counts the number of posts in the database.
     *
     * @param int $offset The offset for the query.
     * 
     * @return int The number of posts found.
     */
    protected function count_posts_in_database(int $offset = 0) : int
    {
        $query = new \WP_Query([
            'post_type' => static::$post_type,
            'posts_per_page' => -1,
            'fields' => 'ids',
            'offset' => $offset
        ]);

        return $query->found_posts;
    }

    /**
     * Ensures the API URL ends with a slash .
     *
     * @return string The formatted API URL.
     */
    protected function ensure_api_url_format(): string
    {
        $url = $this->api_url;
        if (substr($url, -1) !== '/') {
            $url .= '/';
        }
        return $url;
    }

    /**
     * Fetches a page of data from the API.
     *
     * @param string $endpoint_url The endpoint URL to fetch data from.
     * @param string|int $page The page number to fetch.
     * 
     * @return array The fetched data or an error message.
     */
    protected function fetch_page_from_api(string $endpoint_url, string|int $page) : array
    {
        $response = wp_remote_get($endpoint_url . '?page=' . $page);
        if (is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200) {
            $error_msg = __('Error fetching data: ' . wp_remote_retrieve_response_message($response), RICK_MORTY_TEXT_DOMAIN);
            error_log($error_msg);
            return ['fetch_error' => $error_msg];
        }

        $data = json_decode(wp_remote_retrieve_body($response), true);
        if (!isset($data['results']) || !isset($data['info'])) {
            $error_msg = __('Invalid API response', RICK_MORTY_TEXT_DOMAIN);
            error_log($error_msg);
            return ['fetch_error' => $error_msg];
        }
        return $data;
    }

    /**
     * Processes the API data.
     *
     * @param array $items The items to process.
     * 
     * @return void
     */
    protected function process_api_data(array $items) : void
    {
        foreach ($items as $item) {
            $this->save_item($item);
        }
    }

    /**
     * Updates the cache item count.
     *
     * @param string $cache_count_name The name of the cache count.
     * @param int $new_count The new count to update.
     * 
     * @return void
     */
    protected function update_cache_item_count(
        string $cache_count_name,
        int $new_count
    ) : void
    {
        $transient_duration = get_option(RICK_MORTY_PREFIX . 'count_cache_expires', 30 * DAY_IN_SECONDS);
        set_transient($cache_count_name, $new_count, $transient_duration);
    }

    /**
     * Saves a single item from the API.
     *
     * @param array $item The item to save.
     */
    abstract protected function save_item( array $item );
}
