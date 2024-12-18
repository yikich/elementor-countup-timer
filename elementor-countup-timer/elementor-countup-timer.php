<?php
/**
 * Plugin Name: Elementor Count Up Timer
 * Description: A count up timer widget for Elementor
 * Version: 1.0.0
 * Author: Your Name
 */

if (!defined('ABSPATH')) {
    exit;
}

final class Elementor_Countup_Timer {
    const VERSION = '1.0.0';
    const MINIMUM_ELEMENTOR_VERSION = '3.0.0';
    const MINIMUM_PHP_VERSION = '7.0';

    private static $_instance = null;

    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {
        add_action('plugins_loaded', [$this, 'init']);
    }

    public function init() {
        // Check if Elementor is installed and activated
        if (!did_action('elementor/loaded')) {
            add_action('admin_notices', [$this, 'admin_notice_missing_elementor']);
            return;
        }

        // Add Plugin actions
        add_action('elementor/widgets/register', [$this, 'register_widgets']);
        add_action('elementor/frontend/after_register_scripts', [$this, 'register_scripts']);
        add_action('elementor/frontend/after_register_styles', [$this, 'register_styles']);
    }

    public function register_widgets($widgets_manager) {
        require_once(__DIR__ . '/widgets/countup-widget.php');
        $widgets_manager->register(new \Elementor_Countup_Widget());
    }

    public function register_scripts() {
        wp_register_script(
            'elementor-countup',
            plugins_url('assets/js/countup.js', __FILE__),
            [],
            self::VERSION,
            true
        );
    }

    public function register_styles() {
        wp_register_style(
            'elementor-countup',
            plugins_url('assets/css/countup.css', __FILE__),
            [],
            self::VERSION
        );
    }

    public function admin_notice_missing_elementor() {
        if (isset($_GET['activate'])) unset($_GET['activate']);

        $message = sprintf(
            esc_html__('"%1$s" requires "%2$s" to be installed and activated.', 'elementor-countup'),
            '<strong>' . esc_html__('Elementor Count Up Timer', 'elementor-countup') . '</strong>',
            '<strong>' . esc_html__('Elementor', 'elementor-countup') . '</strong>'
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }
}

Elementor_Countup_Timer::instance();