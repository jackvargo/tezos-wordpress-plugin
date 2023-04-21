<?php
/**
 * //TODO: Determine proper name and metadata for this
 * Plugin Name: Tezos WordPress Plugin
 * Plugin URI: https://flipgoal.com
 * Description: A WordPress plugin for integrating Tezos blockchain functionality using the Beacon SDK and Taquito library.
 * Version: 1.0.0
 * Author: Jack Vargo
 * Author URI: https://flipgoal.com
 * License: GPL-2.0-or-later
 * Text Domain: tezos-wp-plugin
 */

// Prevent direct access to the file
if (!defined('ABSPATH')) {
    exit;
}

// Enqueue the plugin's JavaScript file
function tezos_wp_plugin_enqueue_scripts() {
    wp_enqueue_script('tezos-wp-plugin-js', plugin_dir_url(__FILE__) . 'tezos-wp-plugin.js', [], '1.0.0', true);
    wp_enqueue_style('tezos-wp-plugin-php', plugin_dir_url(__FILE__) . 'tezos-wp-plugin.php', [], '1.0.0', true);
}
add_action('wp_enqueue_scripts', 'tezos_wp_plugin_enqueue_scripts');

/**
 * Render the plugin settings page
 */
function tezos_wp_plugin_options() {
    ?>
    <div class="wrap">
        <h1>Tezos WP Plugin Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('tezos_wp_plugin_options');
            do_settings_sections('tezos_wp_plugin_options');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

/**
 * Register plugin settings and create a settings section
 */
function tezos_wp_plugin_settings() {
    register_setting('tezos_wp_plugin_options', 'tezos_wp_plugin_auth_method');

    add_settings_section(
        'tezos_wp_plugin_auth_method_section',
        'Authentication Method',
        null,
        'tezos_wp_plugin_options'
    );

    add_settings_field(
        'tezos_wp_plugin_auth_method',
        'Choose the authentication method',
        'tezos_wp_plugin_auth_method_callback',
        'tezos_wp_plugin_options',
        'tezos_wp_plugin_auth_method_section'
    );

    register_setting('tezos_wp_plugin_options', 'tezos_wp_plugin_rpc_node');


/**
 * TODO: This setting is the site admin option, i.e., configure which nodes are availabe by default for the user
 * TODO: Implement the user-setting for which RPC node they have selected
 * 
 */
 
    add_settings_section(
        'tezos_wp_plugin_rpc_node_section',
        'RPC Node',
        null,
        'tezos_wp_plugin_options'
    );

    add_settings_field(
        'tezos_wp_plugin_rpc_node',
        'Choose the authentication method',
        'tezos_wp_plugin_rpc_node_callback',
        'tezos_wp_plugin_options',
        'tezos_wp_plugin_rpc_node_section'
    );

}
add_action('admin_init', 'tezos_wp_plugin_settings');

/**
 * Render the authentication method selection field
 */
function tezos_wp_plugin_auth_method_callback() {
    $auth_method = get_option('tezos_wp_plugin_auth_method', 'combined');
    ?>
    <select name="tezos_wp_plugin_auth_method">
        <option value="combined" <?php selected($auth_method, 'combined'); ?>>Combined (Traditional Registration + Wallet Authentication)</option>
        <option value="wallet_only" <?php selected($auth_method, 'wallet_only'); ?>>Wallet-only Authentication</option>
    </select>
    <?php
}

/** 
 * Add the plugin settings page to the WordPress admin menu
 */
function tezos_wp_plugin_menu() {
    add_options_page(
        'Tezos WP Plugin Settings',
        'Tezos WP Plugin',
        'manage_options',
        'tezos_wp_plugin_options',
        'tezos_wp_plugin_options'
    );
}
add_action('admin_menu', 'tezos_wp_plugin_menu');


/** 
 * Add a shortcode for displaying the plugin UI as a menu
 */

function tezos_wp_plugin_shortcode() {
    $auth_method = get_option('tezos_wp_plugin_auth_method', 'combined');
    //TODO: Add the right dashboard link
    //TODO: Change the Block Explorer link to the associated wallet address' explorer page.
    ob_start();
    ?>
    <div id="tezos-wp-plugin">
        <button id="connect-wallet" onclick="connectWallet()">Connect Wallet</button>
        <div id="wallet-actions" style="display: none;">
            <button onclick="window.location.href = '/dashboard'">Dashboard</button>
            <button onclick="changeTezosNode()">Change Tezos Node</button>
            <button onclick="window.open('https://tzkt.io')">Open Block Explorer</button>
            <button onclick="disconnectWallet()">Disconnect Wallet</button>
        </div>
        <?php if ($auth_method === 'combined') ?>
            <button id="sync-wallet" style="display: none;" onclick="signMessage()">Sync Wallet</button>
        <?
    ?>
    </div>
    <?php
}
add_shortcode('tezos_wp_plugin', 'tezos_wp_plugin_shortcode');

?>