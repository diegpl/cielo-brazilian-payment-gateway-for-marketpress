<?php
/**
 * Plugin de gateway de pagamento Visa Débito Cielo para MarketPress.
 * 
 * @author Phellipe Kelbert <pkelbert@gmail.com>
 */

/**
 * @see MP_Cielo_Gateway_Debito
 */
require_once 'debito.php';

/**
 * Plugin de gateway de pagamento Visa Débito Cielo.
 * 
 * @author Phellipe Kelbert <pkelbert@gmail.com>
 */
class MP_Cielo_Gateway_Debito_Visa extends MP_Cielo_Gateway_Debito {
    
    /**
     * Slug privado do gateway.
     * @var string
     */
    public $plugin_name = 'visa-debito-cielo';
    
    /**
     * Nome da bandeira do cartão utilizado pela Cielo.
     * @var string
     */
    protected $_bandeira = 'visa';
    
}