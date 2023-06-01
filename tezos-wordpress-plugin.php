<?php
/**
 * //TODO: Determine proper name and metadata for this
 * Plugin Name: Tezos WordPress Plugin
 * Plugin URI: https://flipgoal.com
 * Description: Supercharging WordPress with Web3's Only Evolving Blockchain: A WordPress plugin for integrating Tezos blockchain functionality using the Beacon SDK and Taquito library.
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
    //$output .= '<script src="https://cdn.jsdelivr.net/npm/@airgap/beacon-dapp@4.0.1/dist/cjs/index.min.js"></script>';
    //$output .= '<script src="https://cdn.jsdelivr.net/npm/@taquito/taquito@12.1.1/dist/taquito.min.js"></script>';
    
    // wp_enqueue_script('beacon-sdk-js', 'https://cdn.jsdelivr.net/npm/@airgap/beacon-dapp@4.0.1/dist/cjs/index.min.js', [], '4.0.1', true);
    // wp_enqueue_script('tauquito-sdk-js', 'https://cdn.jsdelivr.net/npm/@taquito/taquito@12.1.1/dist/taquito.min.js', [], '12.1.1', true);
    // wp_enqueue_script('tezos-wp-plugin-js', plugin_dir_url(__FILE__) . 'tezos-wp-plugin.js', [], '1.0.0', true);
    // wp_register_script('beacon-sdk-js', 'https://cdn.jsdelivr.net/npm/@airgap/beacon-sdk@4.0.1/dist/ecma/index.min.js', [], '4.0.1', true);
    // wp_script_add_data('beacon-sdk-js', 'type', 'module');

    // wp_register_script('taquito-sdk-js', 'https://cdn.jsdelivr.net/npm/@taquito/taquito@12.1.1/dist/taquito.min.js', [], '12.1.1', true);
    // wp_script_add_data('taquito-sdk-js', 'type', 'module');

    // wp_enqueue_script('tezos-wp-plugin-js', plugin_dir_url(__FILE__) . 'tezos-wp-plugin.js', ['beacon-sdk-js', 'taquito-sdk-js'], '1.0.0', true);
    // wp_script_add_data('tezos-wp-plugin-js', 'type', 'module');

    wp_enqueue_style('tezos-wp-plugin-css', plugin_dir_url(__FILE__) . 'tezos-wp-plugin.css', [], '1.0.0', true);
    wp_enqueue_script('tezos-wp-plugin-js', plugins_url('dist/tezos-wp-plugin.bundle.js', __FILE__), [], '1.0.0', true);
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

    register_setting(
        'tezos_wp_plugin_options', 
        'tezos_wp_plugin_rpc_nodes',
        array(
            'type' => 'array',
            'sanitize_callback' => 'tezos_wp_plugin_sanitize_rpc_nodes',
        )
    );

    // Register a new settings section
    add_settings_section(
        'tezos_wp_plugin_rpc_nodes_section',
        'Tezos RPC Nodes',
        null,
        'tezos_wp_plugin_options'
    );

    add_settings_field(
        'tezos_wp_plugin_rpc_nodes',
        'Configure the available Tezos RPC Nodes',
        'tezos_wp_plugin_rpc_nodes_section_callback',
        'tezos_wp_plugin_options',
        'tezos_wp_plugin_rpc_nodes_section'
    );
   // Register the RPC nodes setting
    // register_setting(
    //     'tezos_wp_plugin_rpc_nodes',
    //     'tezos_wp_plugin_rpc_nodes',
    //     array(
    //         'type' => 'array',
    //         'sanitize_callback' => 'tezos_wp_plugin_sanitize_rpc_nodes',
    //     )
    // );
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

function tezos_wp_plugin_rpc_nodes_section_callback() {
    $rpc_nodes = get_option('tezos_wp_plugin_rpc_nodes', array());
    $rpc_nodes_text = implode(PHP_EOL, $rpc_nodes);

    echo '<p>Enter the Tezos RPC nodes, one per line:</p>';
    echo "<textarea name=\"tezos_wp_plugin_rpc_nodes\" rows=\"10\" cols=\"50\">$rpc_nodes_text</textarea>";
}


function tezos_wp_plugin_sanitize_rpc_nodes($input) {
    $lines = preg_split('/\r\n|\r|\n/', $input);
    $sanitized_lines = array();

    foreach ($lines as $line) {
        $line = trim($line);
        if (!empty($line) && filter_var($line, FILTER_VALIDATE_URL)) {
            $sanitized_lines[] = $line;
        }
    }

    return $sanitized_lines;
}



function tezos_wp_plugin_rpc_node_callback() {
    // Verify nonce for security
    check_ajax_referer('tezos_wp_plugin_rpc_node_nonce', 'security');

    // Get the new RPC node URL from the POST data
    $new_rpc_node = isset($_POST['new_rpc_node']) ? sanitize_text_field($_POST['new_rpc_node']) : '';

    // Validate the RPC node URL
    if (empty($new_rpc_node) || !filter_var($new_rpc_node, FILTER_VALIDATE_URL)) {
        wp_send_json_error('Invalid RPC node URL');
        return;
    }

    // Save the new RPC node URL in the user meta
    $user_id = get_current_user_id();
    update_user_meta($user_id, 'tezos_rpc_node', $new_rpc_node);

    wp_send_json_success('RPC node URL updated successfully');
}
add_action('wp_ajax_tezos_wp_plugin_rpc_node', 'tezos_wp_plugin_rpc_node_callback');



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
    $rpc_node_nonce = wp_create_nonce('tezos_wp_plugin_rpc_node_nonce');

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

// Define the delegate button shortcode
function tezos_delegate_button_shortcode() {
    // Output the HTML for the button
    return '<button id="tezos-delegate-button">DELEGATE NOW</button>';
}
add_shortcode('tezos_delegate_button', 'tezos_delegate_button_shortcode');

?>