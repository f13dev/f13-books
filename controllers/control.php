<?php namespace F13\Books\Controllers;

class Control
{
    public function __construct()
    {
        add_shortcode('book', array($this, 'book_shortcode'));
    }

    public function get_attachment_id($file_name)
    {
        $m = new \F13\Books\Models\Api();
        return $m->get_image_id($file_name);
    }

    public function get_image()
    {
        if (property_exists($this->data, 'covers') && is_array($this->data->covers) && array_key_exists(0, $this->data->covers)) {
            $url = 'https://covers.openlibrary.org/b/ID/'.$this->data->covers[0].'-L.jpg';
            $this->data->cover = $url;
            $file = $this->data->covers[0].'-L.jpg';
            $image_id = $this->get_attachment_id($file);

            if (!$image_id) {
                require_once(ABSPATH.'wp-admin/includes/media.php');
                require_once(ABSPATH.'wp-admin/includes/file.php');
                require_once(ABSPATH.'wp-admin/includes/image.php');

                media_sideload_image($url, get_the_ID(), ' - Book');
                $image_id = $this->get_attachment_id($file);
                $this->console .= '<script>console.log("Sideloading local image");</script>';
            }

            if ($image_id) {
                $this->data->cover = wp_get_attachment_url($image_id);
                $this->console .= '<script>console.log("Retrieving local image");</script>';
            }
        }
    }

    public function book_shortcode($atts = array())
    {
        extract( shortcode_atts(array ('isbn' => '', 'cache' => 1), $atts ));

        if (empty($cache)) {
            $cache = 1;
        }
        $cache = (int) $cache * 60;
        $this->console = '';

        $cache_key = 'f13_wordpress'.sha1(F13_BOOKS['Version'].'-'.$isbn.'-'.$cache);
        $transient = get_transient( $cache_key );
        if ( $transient ) {
            $return = $transient;
            $return .= '<script>console.log("Building Book from transient: '.$cache_key.'");</script>';
            return $return;
        }

        $this->console .= '<script>console.log("Building Book from API, setting transient: '.$cache_key.'");</script>';

        $m = new \F13\Books\Models\Api();
        $this->data = $m->select_book($isbn);

        if (property_exists($this->data, 'error') && $this->data->error) {
            $e = '<div class="f13-wordpress-error">';
                $e .= __('Error: Book not found.', 'f13-wordpress').': '.$this->data->error;
            $e .= '</div>';
            return $e;
        }

        $this->get_image();

        $v = new \F13\Books\Views\Books(array(
            'data' => $this->data,
        ));

        $return = $v->book();

        set_transient($cache_key, $return, $cache);

        return $this->console.$return;
    }
}