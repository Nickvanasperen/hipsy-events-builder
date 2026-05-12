<?php

/**
 * Hipsy Events Core
 *
 * @package       HIPSY
 * @author        Young Soul Business & How About Yes
 *
 * @wordpress-plugin
 * Plugin Name:   Hipsy Events Core
 * Plugin URI:    https://www.youngsoulbusiness.com
 * Description:   Core plugin voor Hipsy Events. Verzorgt API-koppeling, event sync, custom post type, instellingen en builder-onafhankelijke event data voor WordPress.
 * Version:       4.7.0
 * Author:        Young Soul Business & How About Yes
 * Author URI:    https://www.youngsoulbusiness.com
 * Text Domain:   hipsy-events
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! defined( 'HIPSY_EVENTS_CORE_VERSION' ) ) {
    define( 'HIPSY_EVENTS_CORE_VERSION', '4.7.0' );
}

if ( ! defined( 'HIPSY_EVENTS_CORE_PATH' ) ) {
    define( 'HIPSY_EVENTS_CORE_PATH', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'HIPSY_EVENTS_CORE_URL' ) ) {
    define( 'HIPSY_EVENTS_CORE_URL', plugin_dir_url( __FILE__ ) );
}

$hipsy_puc_file = plugin_dir_path( __FILE__ ) . 'plugin-update-checker/plugin-update-checker.php';

if ( file_exists( $hipsy_puc_file ) ) {
    require_once $hipsy_puc_file;

    if ( class_exists( '\YahnisElsts\PluginUpdateChecker\v5\PucFactory' ) ) {
        \YahnisElsts\PluginUpdateChecker\v5\PucFactory::buildUpdateChecker(
            'https://github.com/Nickvanasperen/hipsy-events-core/',
            __FILE__,
            'hipsy-events-core'
        );
    }
}

if ( file_exists( __DIR__ . "/templates/loopItem.php" ) ) include(__DIR__ . "/templates/loopItem.php");
if ( file_exists( __DIR__ . "/functions/styles.php" ) ) include(__DIR__ . "/functions/styles.php");
if ( file_exists( __DIR__ . "/functions/displayEventsShortcode.php" ) ) include(__DIR__ . "/functions/displayEventsShortcode.php");
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
