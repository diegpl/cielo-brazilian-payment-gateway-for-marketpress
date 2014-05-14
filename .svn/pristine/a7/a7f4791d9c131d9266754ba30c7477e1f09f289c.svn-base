<?php
/**
 * Abstração de plugins de gateway de pagamento pela Cielo para MarketPress.
 * 
 * @author Phellipe Kelbert <pkelbert@gmail.com>
 */

/**
 * Abstração de plugins de gateway de pagamento pela Cielo.
 * 
 * @author Phellipe Kelbert <pkelbert@gmail.com>
 */
abstract class MP_Cielo_Gateway extends MP_Gateway_API {
    
    /**
     * Nome da bandeira do cartão utilizado pela Cielo.
     * @var string
     */
    protected $_bandeira;
    
    /**
     * Indica se uma conexão SSL é obrigatória para a página de pagamento.
     * @var bool
     */
    public $force_ssl = true;

    /**
     * Caso este seja o único gateway habilitado, indica se a seleção da forma de pagamento pode ser pulada.
     * @var bool
     */
    public $skip_form = false;

    /**
     * Exigido somente para gateways com capacidade global. Indica o máximo de lojas que podem ser pagas de uma só vez.
     * @var int
     */
    public $max_stores = 1;

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
     * Indica se o formulário de configurações já foi exibido.
     * @var bool
     */
    protected static $_settings_box = false;
    
    /**
     * Total de vezes em que o formulário de pagamento foi exibido.
     * @var int
     */
    private static $_payment_form_displays = 0;
    
    /**
     * Construtor.
     *
     * @return void
     */
    public function __construct() {
        require_once dirname(__FILE__) . '/../util/registerer.php';
        $registerer = new MP_Cielo_Registerer();
        
        foreach ($registerer->plugins[$this->plugin_name] as $k => $v) {
            if (property_exists($this, $k)) {
                $this->$k = $v;
            }
        }
        
        parent::__construct();
    }
    
    /**
     * Configura o plugin quando é instanciado.
     *
     * @return void
     */
    public function on_creation() {
        global $mp;
        $erro = get_transient('mp_cielo_error');
        if ($erro != '') {
            $mp->cart_checkout_error($erro);
            delete_transient('mp_cielo_error');
        }
        
        require_once dirname(__FILE__) . '/config.php';
        $config           = new MP_Cielo_Config();
        $this->_qa        = $config->is_sandbox();
        $this->_ws_url    = $config->get_url();
        $this->_ec_numero = $config->get_numero_loja();
        $this->_ec_chave  = $config->get_chave_loja();
        $this->_moeda     = $config->get_moeda();
    }

    /**
     * Imprime o formulário de configurações do plugin.
     *
     * @param  array $settings Configurações salvas do MarketPress.
     * @return void
     */
    public function gateway_settings_box($settings) {
        if (self::$_settings_box) {
            return;
        }
        
        require_once dirname(__FILE__) . '/../util/view.php';
        
        $view = new MP_Cielo_View(dirname(__FILE__) . '/../../views/settings/cielo.phtml');
        
        $view->settings = $settings;
        
        $view->ambientes = array(
            'teste'    => __('Teste', 'mp-cielo'),
            'producao' => __('Produção', 'mp-cielo'),
        );
        
        $view->moedas = array(
            '986' => __('Real (R$)', 'mp-cielo'),
            '840' => __('Dólar (US$)', 'mp-cielo'),
        );
        
        echo $view->render();
        
        self::$_settings_box = true;
    }
    
    /**
     * Exibe campos necessários para processar o pagamento.
     *
     * @param  array  $cart          Conteúdo do carrinho de compras.
     * @param  array  $shipping_info Informações de entrega e email.
     * @return string                Conteúdo HTML do formulário.
     */
    public function payment_form($cart, $shipping_info) {
        require_once dirname(__FILE__) . '/../util/view.php';
        
        $view = new MP_Cielo_View(dirname(__FILE__) . '/../../views/payment-form/cielo.phtml');
        $view->displays = self::$_payment_form_displays;
        $view->gateway  = $this;
        
        $result = $view->render();
        
        self::$_payment_form_displays++;
        
        return $result;
    }
    
    /**
     * Retorna o símbolo de uma moeda baseado em seu código.
     *
     * @param  string $moeda Código da moeda.
     * @return string        Símbolo da moeda.
     */
    protected function _simbolo_moeda($moeda) {
        switch ($moeda) {
            case '986': return __('R$', 'mp-cielo');
            case '840': return __('US$', 'mp-cielo');
            default:    return __('$', 'mp-cielo');
        }
    }
    
    /**
     * Calcula o valor total de uma compra.
     *
     * @param  array  $cart Conteúdo do carrinho de compras.
     * @return string       Valor total da compra.
     */
    protected function _total_compra($cart) {
        global $mp;
        
        $total = 0;
        
        // total do valor dos produtos
        foreach ($cart as $product_id => $variations) {
            foreach ($variations as $data) {
                $total += $mp->before_tax_price($data['price'], $product_id) * $data['quantity'];
            }
        }
        
        // novo total em caso de uso de cupom
        if ($coupon = $mp->coupon_value($mp->get_coupon_code(), $total)) {
            $total = $coupon['new_total'];
        }
        
        // taxa de entrega
        if (($shipping_price = $mp->shipping_price()) !== false) {
            $total += $shipping_price;
        }
        
        // taxa de imposto
        if (($tax_price = $mp->tax_price()) !== false) {
            $total += $tax_price;
        }
        
        return number_format(round($total, 2), 2, '.', '');
    }
    
}
