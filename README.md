<p align="center">
  <img src="https://flipgoal.com/wp-content/uploads/2023/06/Color-logo-with-background.png" width=80% alt="Tezos Wordpress Plugin logo" />
</p>

> Supercharging WordPress with Web3's Only Evolving Blockchain: A WordPress plugin for integrating Tezos blockchain functionality using the Beacon SDK and Taquito library.
>

This is a WordPress plugin that integrates Tezos blockchain functionality into your website. It allows users to connect their Tezos wallets, interact with smart contracts, and perform various operations such as delegating XTZ tokens to a baker.

## Features

- Connect to a Tezos wallet using the Beacon SDK
- Interact with smart contracts on the Tezos network
- Retrieve account balances and transaction history
- Delegate XTZ tokens to a baker
- Sign messages for authentication purposes
- Switch between different Tezos nodes

## Requirements

- PHP >= 7.4
- WordPress >= 5.8
- Node.js >= 14.x.x (for development)

## Installation

1. Clone this repository into your `wp-content/plugins` directory:

```git clone https://github.com/yourusername/tezos-wordpress-plugin.git wp-content/plugins/tezos-wordpress-plugin```


2. Navigate to the `Plugins` page in your WordPress admin dashboard and activate the `Tezos WordPress Plugin`.

3. Configure any necessary settings for the plugin in your WordPress admin dashboard.

## Development Setup

1. Install dependencies:

```npm install```


2. Start the Webpack Dev Server:

```npm start```


3. Run the WordPress server using [WP CLI](https://developer.wordpress.org/cli/commands/server/) or another local development environment like [Laravel Valet](https://laravel.com/docs/8.x/valet) or [Local by Flywheel](https://localwp.com/).

4. Make sure that both servers (WordPress server and Webpack Dev Server) are running simultaneously during development.

## Usage

After activating the plugin, you can use provided shortcodes or functions in your theme files to display various Tezos-related features on your website.

For example, you can use the `[tezos_connect_wallet_button]` shortcode to display a "Connect Wallet" button that allows users to connect their Tezos wallets.

## Contributing

Contributions are welcome! Please create a pull request or open an issue if you have any suggestions, bug reports, or feature requests.

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for more information.

