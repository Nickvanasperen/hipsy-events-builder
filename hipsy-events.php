<?php

/**
 * Hipsy Events Core
 *
 * @package       HIPSY
 * @author        How About Yes
 *
 * @wordpress-plugin
 * Plugin Name:   Hipsy Events Core
 * Plugin URI:    https://hipsy.nl
 * Description:   Core plugin voor Hipsy Events. Verzorgt API-koppeling, event sync, custom post type, instellingen en builder-onafhankelijke event data voor WordPress.
 * Version:       4.6.1
 * Author:        How About Yes
 * Author URI:    https://howaboutyes.com
 * Text Domain:   hipsy-events
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! defined( 'HIPSY_EVENTS_CORE_VERSION' ) ) {
    define( 'HIPSY_EVENTS_CORE_VERSION', '4.6.1' );
}

if ( ! defined( 'HIPSY_EVENTS_CORE_PATH' ) ) {
    define( 'HIPSY_EVENTS_CORE_PATH', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'HIPSY_EVENTS_CORE_URL' ) ) {
    define( 'HIPSY_EVENTS_CORE_URL', plugin_dir_url( __FILE__ ) );
}

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

// Event card renderer - builder-onafhankelijke fallback/shortcode rendering
if ( file_exists( plugin_dir_path(__FILE__) . 'render/event-card.php' ) ) {
    include_once plugin_dir_path(__FILE__) . "render/event-card.php";
}

// Individual field shortcodes - builder-onafhankelijk voor custom templates/shortcodes
if ( file_exists( plugin_dir_path(__FILE__) . 'integrations/shortcodes/field-shortcodes.php' ) ) {
    include_once plugin_dir_path(__FILE__) . 'integrations/shortcodes/field-shortcodes.php';
}

// Legacy includes (backwards compatibility) - BASIS PLUGIN
if ( file_exists( __DIR__ . "/templates/loopItem.php" ) ) include(__DIR__ . "/templates/loopItem.php");
if ( file_exists( __DIR__ . "/functions/styles.php" ) ) include(__DIR__ . "/functions/styles.php");
if ( file_exists( __DIR__ . "/functions/displayEventsShortcode.php" ) ) include(__DIR__ . "/functions/displayEventsShortcode.php");

// Oude builder-onafhankelijke shortcodes ALLEEN laden als v4.0 NIET enabled is
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

// ══════════════════════════════════════════════════════
// v4.0 CORE FEATURES - Schakel in via admin settings
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

    // v4.0 builder-onafhankelijke shortcodes
    if ( file_exists( plugin_dir_path(__FILE__) . 'integrations/shortcodes/extended-shortcodes.php' ) ) {
        include_once plugin_dir_path(__FILE__) . 'integrations/shortcodes/extended-shortcodes.php';
    }
}
