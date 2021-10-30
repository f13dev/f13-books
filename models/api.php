<?php namespace F13\Books\Models;

class Api
{
    public function __construct()
    {
        $this->api_url = 'https://openlibrary.org/'; 
        $this->api_url_isbn = $this->api_url.'isbn/';
    }

    public function _call($endpoint) {
        $response = wp_remote_get($endpoint);
        $body     = wp_remote_retrieve_body($response);

        return (object) json_decode($body);
    }

    public function select_book($isbn)
    {
        $return = $this->_call($this->api_url_isbn.$isbn.'.json');

        return $return;
    }

    public function get_image_id($file_name)
    {
        global $wpdb;

        $sql = "SELECT post_id
                FROM ".$wpdb->base_prefix."postmeta
                WHERE meta_key = '_wp_attached_file' AND meta_value LIKE %s;";
        $attachment = $wpdb->get_var($wpdb->prepare($sql, '%'.$file_name));

        return $attachment;
    }
}