<?php
/**
 * Registrador de plugins Cielo junto ao MarketPress.
 *
 * @author Phellipe Kelbert <pkelbert@gmail.com>
 */

/**
 * Registrador de plugins Cielo junto ao MarketPress.
 *
 * @author Phellipe Kelbert <pkelbert@gmail.com>
 */
class MP_Cielo_Registerer {
    
    /**
     * Informações dos plugins registrados junto ao MarketPress.
     * @var array
     */
    public $plugins;
    
    /**
     * Construtor.
     *
     * @return void
     */
    public function __construct() {
        global $mp;
        $this->plugins = array(
            'visa-debito-cielo'         => array(
                'class'                 => 'MP_Cielo_Gateway_Debito_Visa',
                'file'                  => 'visa-debito.php',
                'admin_name'            => __('Cartão de débito Visa Electron via Cielo', 'mp-cielo'),
                'public_name'           => __('Visa Electron', 'mp-cielo'),
                'method_img_url'        => $mp->plugin_url . 'plugins-gateway/cielo/imagens/visa-electron.gif',
                'method_button_img_url' => $mp->plugin_url . 'plugins-gateway/cielo/imagens/visa-electron.gif',
            ),
            'visa-credito-cielo'        => array(
                'class'                 => 'MP_Cielo_Gateway_Credito_Visa',
                'file'                  => 'visa-credito.php',
                'admin_name'            => __('Cartão de crédito Visa via Cielo', 'mp-cielo'),
                'public_name'           => __('Visa', 'mp-cielo'),
                'method_img_url'        => $mp->plugin_url . 'plugins-gateway/cielo/imagens/visa.gif',
                'method_button_img_url' => $mp->plugin_url . 'plugins-gateway/cielo/imagens/visa.gif',
            ),
            'mastercard-credito-cielo'  => array(
                'class'                 => 'MP_Cielo_Gateway_Credito_MasterCard',
                'file'                  => 'mastercard-credito.php',
                'admin_name'            => __('Cartão de crédito MasterCard via Cielo', 'mp-cielo'),
                'public_name'           => __('MasterCard', 'mp-cielo'),
                'method_img_url'        => $mp->plugin_url . 'plugins-gateway/cielo/imagens/mastercard.gif',
                'method_button_img_url' => $mp->plugin_url . 'plugins-gateway/cielo/imagens/mastercard.gif',
            ),
            'amex-credito-cielo'  => array(
                'class'                 => 'MP_Cielo_Gateway_Credito_Amex',
                'file'                  => 'amex-credito.php',
                'admin_name'            => __('Cartão de crédito American Express via Cielo', 'mp-cielo'),
                'public_name'           => __('American Express', 'mp-cielo'),
                'method_img_url'        => $mp->plugin_url . 'plugins-gateway/cielo/imagens/amex.gif',
                'method_button_img_url' => $mp->plugin_url . 'plugins-gateway/cielo/imagens/amex.gif',
            ),
            'elo-credito-cielo'  => array(
                'class'                 => 'MP_Cielo_Gateway_Credito_Elo',
                'file'                  => 'elo-credito.php',
                'admin_name'            => __('Cartão de crédito Elo via Cielo', 'mp-cielo'),
                'public_name'           => __('Elo', 'mp-cielo'),
                'method_img_url'        => $mp->plugin_url . 'plugins-gateway/cielo/imagens/elo.gif',
                'method_button_img_url' => $mp->plugin_url . 'plugins-gateway/cielo/imagens/elo.gif',
            ),
            'diners-credito-cielo'  => array(
                'class'                 => 'MP_Cielo_Gateway_Credito_Diners',
                'file'                  => 'diners-credito.php',
                'admin_name'            => __('Cartão de crédito Diners Club International via Cielo', 'mp-cielo'),
                'public_name'           => __('Diners Club International', 'mp-cielo'),
                'method_img_url'        => $mp->plugin_url . 'plugins-gateway/cielo/imagens/dci.gif',
                'method_button_img_url' => $mp->plugin_url . 'plugins-gateway/cielo/imagens/dci.gif',
            ),
            'discover-credito-cielo'  => array(
                'class'                 => 'MP_Cielo_Gateway_Credito_Discover',
                'file'                  => 'discover-credito.php',
                'admin_name'            => __('Cartão de crédito Discover via Cielo', 'mp-cielo'),
                'public_name'           => __('Discover', 'mp-cielo'),
                'method_img_url'        => $mp->plugin_url . 'plugins-gateway/cielo/imagens/discover.gif',
                'method_button_img_url' => $mp->plugin_url . 'plugins-gateway/cielo/imagens/discover.gif',
            ),
            'jcb-credito-cielo'  => array(
                'class'                 => 'MP_Cielo_Gateway_Credito_Jcb',
                'file'                  => 'jcb-credito.php',
                'admin_name'            => __('Cartão de crédito JCB via Cielo', 'mp-cielo'),
                'public_name'           => __('JCB', 'mp-cielo'),
                'method_img_url'        => $mp->plugin_url . 'plugins-gateway/cielo/imagens/jcb.gif',
                'method_button_img_url' => $mp->plugin_url . 'plugins-gateway/cielo/imagens/jcb.gif',
            ),
            'aura-credito-cielo'  => array(
                'class'                 => 'MP_Cielo_Gateway_Credito_Aura',
                'file'                  => 'aura-credito.php',
                'admin_name'            => __('Cartão de crédito Aura via Cielo', 'mp-cielo'),
                'public_name'           => __('Aura', 'mp-cielo'),
                'method_img_url'        => $mp->plugin_url . 'plugins-gateway/cielo/imagens/aura.gif',
                'method_button_img_url' => $mp->plugin_url . 'plugins-gateway/cielo/imagens/aura.gif',
            ),
        );
    }
    
    /**
     * Registra os plugins juntos ao MarketPress.
     *
     * @return void
     */
    public function register_plugins() {
        foreach ($this->plugins as $plugin_name => $info) {
            require_once dirname(__FILE__) . '/../gateway/' . $info['file'];
            mp_register_gateway_plugin($info['class'], $plugin_name, $info['admin_name']);
        }
    }
    
}