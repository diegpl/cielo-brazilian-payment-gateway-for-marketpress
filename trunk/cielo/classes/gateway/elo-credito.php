<?php
/**
 * Plugin de gateway de pagamento Elo Crédito Cielo para MarketPress.
 * 
 * @author Phellipe Kelbert <pkelbert@gmail.com>
 */

/**
 * @see MP_Cielo_Gateway_Credito
 */
require_once 'credito.php';

/**
 * Plugin de gateway de pagamento Elo Crédito Cielo.
 * 
 * @author Phellipe Kelbert <pkelbert@gmail.com>
 */
class MP_Cielo_Gateway_Credito_Elo extends MP_Cielo_Gateway_Credito {
    
    /**
     * Slug privado do gateway.
     * @var string
     */
    public $plugin_name = 'elo-credito-cielo';
    
    /**
     * Nome da bandeira do cartão utilizado pela Cielo.
     * @var string
     */
    protected $_bandeira = 'elo';
    
}