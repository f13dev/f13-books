<?php
/*
Plugin Name: F13 Books
Plugin URI: https://f13.dev/wordpress-plugins/wordpress-plugin-books/
Description: Display book information on your blog with a shortcode
Version: 1.0.0
Author: F13Dev
Author URI: https://f13.dev
Text Domain: f13-books
*/

namespace F13\Books;

if (!function_exists('get_plugins')) require_once(ABSPATH.'wp-admin/includes/plugin.php');
if (!defined('F13_BOOKS')) define('F13_BOOKS', get_plugin_data(__FILE__, false, false));
if (!defined('F13_BOOKS_PATH')) define('F13_BOOKS_PATH', plugin_dir_path( __FILE__ ));
if (!defined('F13_BOOKS_URL')) define('F13_BOOKS_URL', plugin_dir_url(__FILE__));

class Plugin
{
    public function init()
    {
        spl_autoload_register(__NAMESPACE__.'\Plugin::loader');

        add_action('wp_enqueue_scripts', array($this, 'enqueue'));

        $c = new Controllers\Control();
    }

    public static function loader($name)
    {
        $name = trim(ltrim($name, '\\'));
        if (strpos($name, __NAMESPACE__) !== 0) {
            return;
        }
        $file = str_replace(__NAMESPACE__, '', $name);
        $file = ltrim(str_replace('\\', DIRECTORY_SEPARATOR, $file), DIRECTORY_SEPARATOR);
        $file = plugin_dir_path(__FILE__).strtolower($file).'.php';

        if ($file !== realpath($file) || !file_exists($file)) {
            wp_die('Class not found: '.htmlentities($name));
        } else {
            require_once $file;
        }
    }

    public function enqueue()
    {
        wp_enqueue_style('f13-books', F13_BOOKS_URL.'css/f13-books.css', array(), F13_BOOKS['Version']);
        if (file_exists(F13_BOOKS_URL.'css/override.css')) {
            wp_enqueue_style('f13-books-override', F13_BOOKS_URL.'css/override.css', array(), F13_BOOKS['Version']);
        }
    }
}

$p = new Plugin();
$p->init();