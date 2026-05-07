<?php

/**
 * Hipsy Events Builder
 *
 * @package       HIPSY
 * @author        How About Yes
 *
 * @wordpress-plugin
 * Plugin Name:   Hipsy Events Builder
 * Plugin URI:    https://hipsy.nl
 * Description:   Professional event builder plugin voor WordPress. Builder-onafhankelijk met Elementor-first approach. Sync Hipsy events en creëer custom event designs met filters, responsive layouts en carousel support.
 * Version:       4.5.1
 * Author:        How About Yes
 * Author URI:    https://howaboutyes.com
 * Text Domain:   hipsy-events
 */

// Plugin Update Checker
require plugin_dir_path(__FILE__) . 'plugin-update-checker/plugin-update-checker.php';
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;
$hipsy_updater = PucFactory::buildUpdateChecker('https://github.com/nickvanasperen/hipsy-events-builder/', __FILE__, 'hipsy-events-builder');
$hipsy_updater->getVcsApi()->enableReleaseAssets();

// ══════════════════════════════════════════════════════
// CORE INCLUDES - Basis functionaliteit (altijd actief)
// ══════════════════════════════════════════════════════

// Helpers
if ( file_exists( plugin_dir_path(__FILE__) . 'includes/helpers.php' ) ) {
    include_once plugin_dir_path(__FILE__) . "includes/helpers.php";
}

// Event card renderer (gebruikt door Elementor, Divi en shortcodes)
if ( file_exists( plugin_dir_path(__FILE__) . 'render/event-card.php' ) ) {
    include_once plugin_dir_path(__FILE__) . "render/event-card.php";
}

// Individual field shortcodes (ALTIJD laden - voor custom templates)
if ( file_exists( plugin_dir_path(__FILE__) . 'integrations/shortcodes/field-shortcodes.php' ) ) {
    include_once plugin_dir_path(__FILE__) . 'integrations/shortcodes/field-shortcodes.php';
}

// Legacy includes (backwards compatibility) - BASIS PLUGIN
if ( file_exists( __DIR__ . "/templates/loopItem.php" ) ) include(__DIR__ . "/templates/loopItem.php");
if ( file_exists( __DIR__ . "/functions/styles.php" ) ) include(__DIR__ . "/functions/styles.php");
if ( file_exists( __DIR__ . "/functions/displayEventsShortcode.php" ) ) include(__DIR__ . "/functions/displayEventsShortcode.php");

// Oude shortcodes ALLEEN laden als v4.0 NIET enabled is
// (v4.0 heeft eigen extended shortcodes)
if ( ! get_option('hipsy_events_v4_enabled', 0) ) {
    if ( file_exists( __DIR__ . "/functions/builderShortcodes.php" ) ) include(__DIR__ . "/functions/builderShortcodes.php");
}

if ( file_exists( __DIR__ . "/functions/deleteOldEvents.php" ) ) include(__DIR__ . "/functions/deleteOldEvents.php");
if ( file_exists( __DIR__ . "/functions/createEvent.php" ) ) include(__DIR__ . "/functions/createEvent.php");
if ( file_exists( __DIR__ . "/functions/getHipsyEvents.php" ) ) include(__DIR__ . "/functions/getHipsyEvents.php");
if ( file_exists( __DIR__ . "/functions/displaySettings.php" ) ) include(__DIR__ . "/functions/displaySettings.php");
if ( file_exists( __DIR__ . "/functions/initSettings.php" ) ) include(__DIR__ . "/functions/initSettings.php");
if ( file_exists( __DIR__ . "/functions/submitSettings.php" ) ) include(__DIR__ . "/functions/submitSettings.php");
if ( file_exists( __DIR__ . "/functions/customPostType.php" ) ) include(__DIR__ . "/functions/customPostType.php");
if ( file_exists( __DIR__ . "/functions/submenuItem.php" ) ) include(__DIR__ . "/functions/submenuItem.php");
if ( file_exists( __DIR__ . "/functions/customFields.php" ) ) include(__DIR__ . "/functions/customFields.php");
if ( file_exists( __DIR__ . "/functions/singleEventRenderer.php" ) ) include(__DIR__ . "/functions/singleEventRenderer.php");
if ( file_exists( __DIR__ . "/functions/customTemplates.php" ) ) include(__DIR__ . "/functions/customTemplates.php");
if ( file_exists( __DIR__ . "/functions/adminColumns.php" ) ) include(__DIR__ . "/functions/adminColumns.php");
if ( file_exists( __DIR__ . "/functions/restApiSorting.php" ) ) include(__DIR__ . "/functions/restApiSorting.php");
if ( file_exists( __DIR__ . "/functions/blockGutenberg.php" ) ) include(__DIR__ . "/functions/blockGutenberg.php");
if ( file_exists( __DIR__ . "/functions/cronJob.php" ) ) include(__DIR__ . "/functions/cronJob.php");
if ( file_exists( __DIR__ . "/functions/ajaxLoadMore.php" ) ) include(__DIR__ . "/functions/ajaxLoadMore.php");

// Elementor integration (LEGACY - altijd laden)
if ( file_exists( plugin_dir_path(__FILE__) . 'functions/elementorWidgets.php' ) ) {
    include_once plugin_dir_path(__FILE__) . 'functions/elementorWidgets.php';
}

// Elementor Dynamic Tags (ALTIJD laden - voor dropdown menu)
if ( file_exists( plugin_dir_path(__FILE__) . 'integrations/elementor/elementor-dynamic-tags.php' ) ) {
    include_once plugin_dir_path(__FILE__) . 'integrations/elementor/elementor-dynamic-tags.php';
}

// Divi integration (ALTIJD laden - werkt alleen als Divi actief is)
if ( file_exists( plugin_dir_path(__FILE__) . 'integrations/divi/divi-loader.php' ) ) {
    include_once plugin_dir_path(__FILE__) . 'integrations/divi/divi-loader.php';
}

// Flatsome (UX Builder) integration (NIEUW in v4.4.0)
if ( file_exists( plugin_dir_path(__FILE__) . 'integrations/flatsome/flatsome-loader.php' ) ) {
    include_once plugin_dir_path(__FILE__) . 'integrations/flatsome/flatsome-loader.php';
}

// ══════════════════════════════════════════════════════
// v4.0 FEATURES - Schakel in via admin settings
// ══════════════════════════════════════════════════════
// Ga naar: Dashboard → Events → Settings → v4.0 Features
// ══════════════════════════════════════════════════════

$v4_enabled = get_option('hipsy_events_v4_enabled', 0);

if ( $v4_enabled ) {
    
    // Core systemen (v4.0)
    if ( file_exists( plugin_dir_path(__FILE__) . 'core/query-system.php' ) ) {
        include_once plugin_dir_path(__FILE__) . "core/query-system.php";
    }
    
    if ( file_exists( plugin_dir_path(__FILE__) . 'core/ajax-filter.php' ) ) {
        include_once plugin_dir_path(__FILE__) . "core/ajax-filter.php";
    }
    
    if ( file_exists( plugin_dir_path(__FILE__) . 'render/event-card.php' ) ) {
        include_once plugin_dir_path(__FILE__) . "render/event-card.php";
    }
    
    // v4.0 Extended shortcodes
    if ( file_exists( plugin_dir_path(__FILE__) . 'integrations/shortcodes/extended-shortcodes.php' ) ) {
        include_once plugin_dir_path(__FILE__) . 'integrations/shortcodes/extended-shortcodes.php';
    }
    
    // v4.0 Elementor Filter Widget
    if ( file_exists( plugin_dir_path(__FILE__) . 'integrations/elementor/filter-bar-widget.php' ) ) {
        add_action( 'elementor/widgets/register', 'hipsy_register_v4_widgets' );
    }
    
    function hipsy_register_v4_widgets( $widgets_manager ) {
        $widget_file = plugin_dir_path(__FILE__) . 'integrations/elementor/filter-bar-widget.php';
        
        if ( ! file_exists( $widget_file ) ) {
            return;
        }
        
        require_once $widget_file;
        
        if ( class_exists( 'Hipsy_Filter_Bar_Widget' ) ) {
            $widgets_manager->register( new Hipsy_Filter_Bar_Widget() );
        }
    }
}
