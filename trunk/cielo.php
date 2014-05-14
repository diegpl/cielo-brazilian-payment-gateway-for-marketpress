<?php

/*


Plugin: CIELO for Marketpress - Brazilian Credit Cards
Theme URI: http://wordpress.org/plugins/cielo-for-marketpress-brazilian-credit-cards
Author: Phellipe Kelbert <pkelbert@gmail.com>
Author URI: http://wpsoft.com.br/
Description:
Payment gateway plugin by CIELO Brazilian Credit Cards, for Marketpress.
Plugin de gateway de pagamento pela Cielo para MarketPress.
Version: 1.0
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Tags: https,cielo,e-commerce,https,ssl,brazilian credit cards,credit cards, payment gateway, marketpress plugin,marketpress brazilian cielo,marketpress brazilian credit cards
Text Domain: twentythirteen


 */

// suporte à função lcfirst para versões do php anteriores à 5.3
if (!function_exists('lcfirst')) {
    function lcfirst($str) {
        $str{0} = strtolower($str{0});
        return $str;
    }
}

/**
 * @see MP_Cielo_OrderListener
 */
require_once 'cielo/classes/util/order-listener.php';
$mp_cielo_listener = new MP_Cielo_OrderListener();
add_action('order_paid_to_trash', array($mp_cielo_listener, 'cancel_order'));

/**
 * @see MP_Cielo_Registerer
 */
require_once 'cielo/classes/util/registerer.php';
$mp_cielo_registerer = new MP_Cielo_Registerer();
$mp_cielo_registerer->register_plugins();
