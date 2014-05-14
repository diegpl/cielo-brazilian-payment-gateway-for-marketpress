<?php
/**
 * Configurações gerais de pagamentos pela Cielo para MarketPress.
 * 
 * @author Phellipe Kelbert <pkelbert@gmail.com>
 */

/**
 * Configurações gerais de pagamentos pela Cielo.
 * 
 * @author Phellipe Kelbert <pkelbert@gmail.com>
 */
class MP_Cielo_Config {
    
    /**
     * Indica se as transações devem ser feitas no ambiente de teste.
     * @var bool
     */
    protected $_qa;
    
    /**
     * Número de afiliação da loja com a Cielo.
     * @var string
     */
    protected $_ec_numero;
    
    /**
     * Chave de acesso da loja atribuída pela Cielo.
     * @var string
     */
    protected $_ec_chave;
    
    /**
     * Código da moeda utilizada nas transações.
     * @var string
     */
    protected $_moeda;
    
    /**
     * URL do Web Service Cielo a ser utilizado.
     * @var string
     */
    protected $_ws_url;
    
    /**
     * Construtor.
     *
     * @return void
     */
    public function __construct() {
        $settings = get_option('mp_settings');
        
        if (!isset($settings['gateways']['cielo'])) {
            $this->_qa = true;
        } else {
            $this->_qa = ($settings['gateways']['cielo']['ambiente'] == 'teste');
        }

        if ($this->_qa) {
            $this->_ws_url = 'https://qasecommerce.cielo.com.br/servicos/ecommwsec.do';
            $this->_ec_numero = '1006993069';
            $this->_ec_chave = '25fbb99741c739dd84d7b06ec78c9bac718838630f30b112d033ce2e621b34f3';
            $this->_moeda = '986';
        } else {
            $this->_ws_url = 'https://ecommerce.cielo.com.br/servicos/ecommwsec.do';
            $this->_ec_numero = $settings['gateways']['cielo']['ec_numero'];
            $this->_ec_chave = $settings['gateways']['cielo']['ec_chave'];
            $this->_moeda = $settings['gateways']['cielo']['moeda'];
        }
    }
    
    /**
     * Retorna se as transações devem ser feitas no ambiente de testes.
     *
     * @return bool
     */
    public function is_sandbox() {
        return $this->_qa;
    }
    
    /**
     * Retorna o número de afiliação da loja com a Cielo.
     *
     * @return string
     */
    public function get_numero_loja() {
        return $this->_ec_numero;
    }
    
    /**
     * Retorna a chave de acesso da loja atribuída pela Cielo.
     *
     * @return string
     */
    public function get_chave_loja() {
        return $this->_ec_chave;
    }
    
    /**
     * Retorna o código da moeda utilizada nas transações.
     *
     * @return string
     */
    public function get_moeda() {
        return $this->_moeda;
    }
    
    /**
     * Retorna a URL do Web Service Cielo a ser utilizado.
     *
     * @return string
     */
    public function get_url() {
        return $this->_ws_url;
    }
    
}