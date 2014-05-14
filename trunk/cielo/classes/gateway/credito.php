<?php
/**
 * Abstração de plugins de gateway de pagamento via cartão de crédito pela Cielo para MarketPress.
 * 
 * @author Phellipe Kelbert <pkelbert@gmail.com>
 */

/**
 * @see MP_Cielo_Gateway
 */
require_once 'cielo.php';

/**
 * Abstração de plugins de gateway de pagamento via cartão de crédito pela Cielo.
 * 
 * @author Phellipe Kelbert <pkelbert@gmail.com>
 */
abstract class MP_Cielo_Gateway_Credito extends MP_Cielo_Gateway {
    
    /**
     * Indica se o cartão aceita parcelamento.
     * @var bool
     */
    protected $_parcelamento = true;
    
    /**
     * Tipo de parcelamento.
     *
     * Pode assumir os seguintes valores:
     * - "2": Parcelamento pela loja.
     * - "3": Parcelamento pela administradora.
     *
     * @var string
     */
    protected $_tipo_parcelamento;
    
    /**
     * Número máximo de parcelas.
     * @var int
     */
    protected $_max_parcelas = 1;
    
    /**
     * Valor mínimo das parcelas.
     * @var float
     */
    protected $_min_valor_parcelas = 5;
    
    /**
     * Configura o plugin quando é instanciado.
     *
     * @return void
     */
    public function on_creation() {
        parent::on_creation();
        
        $settings = get_option('mp_settings');
        
        if (!isset($settings['gateways'][$this->plugin_name])) {
            return;
        }
        
        $this->_tipo_parcelamento = $settings['gateways'][$this->plugin_name]['tipo_parcelamento'];
        $this->_max_parcelas = $settings['gateways'][$this->plugin_name]['max_parcelas'];
        $this->_valor_min_parcelas = $settings['gateways'][$this->plugin_name]['valor_min_parcelas'];
    }
    
    /**
     * Imprime o formulário de configurações do plugin.
     *
     * @param  array $settings Configurações salvas do MarketPress.
     * @return void
     */
    public function gateway_settings_box($settings) {
        global $wp_filters, $mp;
        
        parent::gateway_settings_box($settings);
        
        if (!$this->_parcelamento) {
            return;
        }
        
        require_once dirname(__FILE__) . '/../util/view.php';
        
        $view = new MP_Cielo_View(dirname(__FILE__) . '/../../views/settings/credito.phtml');
        
        $view->plugin_name = $this->plugin_name;
        $view->admin_name = $this->admin_name;
        $view->settings = $settings;
        
        $view->tipo_parcelamento = array(
            '2' => __('Parcelado loja', 'mp-cielo'),
            // a modalidade abaixo foi desabilitada por estar sujeita a juros definido pelo banco emissor
            //'3' => __('Parcelado administradora', 'mp-cielo'),
        );
        
        $max_parcelas = array();
        for ($parcela = 1; $parcela <= 18; $parcela++) {
            $max_parcelas[$parcela] = $parcela . 'x';
        }
        $view->max_parcelas = $max_parcelas;
        
        $valor_min_parcelas = array();
        for ($valor = 5; $valor <= 50; $valor += 5) {
            $valor_min_parcelas[$valor] = $mp->format_currency('', $valor);
        }
        $view->valor_min_parcelas = $valor_min_parcelas;
        
        echo $view->render();
    }
    
    /**
     * Calcula as formas de parcelamento disponíveis.
     *
     * @param  array $cart Conteúdo do carrinho de compras.
     * @return array       Formas de parcelamento.
     */
    protected function _parcelamento($cart) {
        global $mp;
        
        $total = (float)$this->_total_compra($cart);
        $parcelas = array('1' => sprintf('%s (%s)', __('À vista', 'mp-cielo'), $mp->format_currency('', $total)));
        
        if ($this->_max_parcelas == 1) {
            return $parcelas;
        }
        
        // diminui o número máximo de parcelas caso excedam o mínimo
        $max_parcelas = $this->_max_parcelas;
        while ($total / $max_parcelas < $this->_valor_min_parcelas) {
            $max_parcelas--;
        }
        
        for ($parcela = 2; $parcela <= $max_parcelas; $parcela++) {
            $valor = round($total / $parcela, 2);
            $parcelas[$parcela] = sprintf('%dx %s %s %s', $parcela, __('de', 'mp-cielo'), $mp->format_currency('', $valor), __('sem juros', 'mp-cielo'));
        }
        
        return $parcelas;
    }
    
    /**
     * Exibe campos necessários para processar o pagamento.
     *
     * @param  array  $cart          Conteúdo do carrinho de compras.
     * @param  array  $shipping_info Informações de entrega e email.
     * @return string                Conteúdo HTML do formulário.
     */
    public function payment_form($cart, $shipping_info) {
        $result = parent::payment_form($cart, $shipping_info);
        
        require_once dirname(__FILE__) . '/../util/view.php';
        $view = new MP_Cielo_View(dirname(__FILE__) . '/../../views/payment-form/credito.phtml');
        $view->gateway  = $this;
        
        $campos = array('parcelas', 'numero', 'mes_validade', 'ano_validade', 'codigo');
        foreach ($campos as $campo) {
            $view->$campo = (isset($_POST[$this->plugin_name][$campo]) ? $_POST[$this->plugin_name][$campo] : '');
        }
        
        $view->parcelamento = $this->_parcelamento($cart);
        
        $meses = array();
        for ($mes = 1; $mes <= 12; $mes++) {
            $meses[$mes] = (strlen($mes) == 1 ? '0' . $mes : $mes);
        }
        $view->meses_validade = $meses;
        
        $anos = array();
        for ($ano = date('Y'); $ano <= date('Y') + 15; $ano++) {
            $anos[$ano] = $ano;
        }
        $view->anos_validade = $anos;
        
        $result .= $view->render();
        
        return $result;
    }
    
    /**
     * Processa o formulário de dados para o pagamento.
     *
     * @param  array $cart          Conteúdo do carrinho de compras.
     * @param  array $shipping_info Informações de entrega e email.
     * @return void
     */
    public function process_payment_form($cart, $shipping_info) {
        global $mp;
        
        $campos = array(
            'parcelas'     => __('É necessário informar o número de parcelas.', 'mp-cielo'),
            'numero'       => __('É necessário informar o número do cartão.', 'mp-cielo'),
            'validade'     => array(
                array('mes_validade', 'ano_validade'),
                __('É necessário informar a validade do cartão.', 'mp-cielo'),
            ),
            'codigo'       => __('É necessário informar o código de segurança do cartão.', 'mp-cielo'),
        );
        
        foreach ($campos as $k => $v) {
            if (is_array($v)) {
                foreach ($v[0] as $f) {
                    if (!isset($_POST[$this->plugin_name][$f]) || empty($_POST[$this->plugin_name][$f])) {
                        $mp->cart_checkout_error( __($v[1], 'mp-cielo'), $k);
                        break;
                    }
                }
            } elseif (!isset($_POST[$this->plugin_name][$k]) || empty($_POST[$this->plugin_name][$k])) {
                $mp->cart_checkout_error( __($v, 'mp-cielo'), $k);
            }
        }
        
        if ($mp->checkout_error) {
            return;
        }
        
        foreach ($campos as $k => $v) {
            if (is_array($v)) {
                foreach ($v[0] as $f) {
                    $_SESSION[$this->plugin_name][$f] = $_POST[$this->plugin_name][$f];
                }
            } else {
                $_SESSION[$this->plugin_name][$k] = $_POST[$this->plugin_name][$k];
            }
        }
    }
    
    /**
     * Exibe detalhes do pagamento para confirmação.
     *
     * @param  array  $cart          Conteúdo do carrinho de compras.
     * @param  array  $shipping_info Informações de entrega e email.
     * @return string                Conteúdo HTML do formulário.
     */
    public function confirm_payment_form($cart, $shipping_info) {
        require_once dirname(__FILE__) . '/../util/view.php';
        
        $view = new MP_Cielo_View(dirname(__FILE__) . '/../../views/confirm-payment/credito.phtml');
        $view->gateway = $this;
        
        $parcelas = $this->_parcelamento($cart);
        
        $view->parcelas = $parcelas[$_SESSION[$this->plugin_name]['parcelas']];
        $view->numero = $_SESSION[$this->plugin_name]['numero'];
        
        return $view->render();
    }
    
    /**
     * Efetiva o pagamento da compra.
     *
     * @param  array  $cart          Conteúdo do carrinho de compras.
     * @param  array  $shipping_info Informações de entrega e email.
     * @return void
     */
    public function process_payment($cart, $shipping_info) {
        global $mp;
        
        require_once dirname(__FILE__) . '/../mensagem/requisicao-transacao.php';
        
        // dados da loja
        $loja = new MP_Cielo_Loja();
        $loja->numero = $this->_ec_numero;
        $loja->chave  = $this->_ec_chave;
        
        // dados do cartão
        $cartao = new MP_Cielo_Cartao();
        $cartao->numero       = $_SESSION[$this->plugin_name]['numero'];
        $cartao->mes_validade = $_SESSION[$this->plugin_name]['mes_validade'];
        $cartao->ano_validade = $_SESSION[$this->plugin_name]['ano_validade'];
        $cartao->indicador    = MP_Cielo_Cartao::INDICADOR_INFORMADO;
        $cartao->codigo       = $_SESSION[$this->plugin_name]['codigo'];
        
        // dados do pedido
        $pedido = new MP_Cielo_Pedido();
        $pedido->numero    = $mp->generate_order_id();
        $pedido->valor     = $this->_total_compra($cart);
        $pedido->moeda     = $this->_moeda;
        $pedido->datahora  = time();
        $pedido->descricao = __('Origem: ', 'mp-cielo') . $_SERVER['REMOTE_ADDR'];
        
        // dados do pagamento
        $pagamento = new MP_Cielo_Pagamento();
        $pagamento->bandeira = $this->_bandeira;
        $pagamento->parcelas = $_SESSION[$this->plugin_name]['parcelas'];
        $pagamento->produto  = ($pagamento->parcelas == 1 ? MP_Cielo_Pagamento::CREDITO_A_VISTA : $this->_tipo_parcelamento);
        
        // monta a requisição de transação
        $requisicao = new MP_Cielo_RequisicaoTransacao($loja, $cartao, $pedido, $pagamento);
        $requisicao->autorizacao = MP_Cielo_RequisicaoTransacao::AUTORIZACAO_DIRETA;
        $requisicao->capturar    = true;
        
        // envia a requisição à cielo e coleta a resposta da operação
        $resposta = $requisicao->enviar($this->_ws_url, $erro);
        
        // coleta a data e a hora do retorno
        $datahora_retorno = time();
        
        // verifica se houve erro no envio
        if ($resposta === false) {
            $mp->cart_checkout_error($erro);
            return;
        }
        
        // verifica se a cielo não autorizou a transação
        if ((string)$resposta->status != 6) {
            $mp->cart_checkout_error(__('Transação não autorizada pela administradora do cartão.', 'mp-cielo'));
            return;
        }
        
        // opções de parcelamento
        $parcelas = $this->_parcelamento($cart);
        
        // define dados do pagamento
        $payment_info = array(
            'status'                => array(
                $pedido->datahora   => __('Pedido confirmado; solicitação de pagamento enviada à Cielo.', 'mp-cielo'),
                $datahora_retorno   => __('Pagamento confirmado pela Cielo.', 'mp-cielo'),
            ),
            'gateway_public_name'   => $this->public_name,
            'gateway_private_name'  => $this->admin_name,
            'method'                => $parcelas[$pagamento->parcelas],
            'transaction_id'        => (string)$resposta->tid,
            'total'                 => $pedido->valor,
            'currency'              => $mp->get_setting('currency'),
        );
        
        // cria o pedido
        $mp->create_order($pedido->numero, $cart, $shipping_info, $payment_info, true);
    }
    
    /**
     * Executado antes do carregamento da página de confirmação do pedido.
     *
     * @param  WP_Post $order Post de representação do pedido.
     * @return void
     */
    public function order_confirmation($order) {
        // o que o cavalo foi fazer no orelhão? passar um trote.
    }
    
    /**
     * Retorna HTML a ser adicionado à página de confirmação do pedido.
     *
     * @param  string  $content Conteúdo HTML atual.
     * @param  WP_Post $order   Post de representação do pedido.
     * @return string           Conteúdo HTML resultante.
     */
    public function order_confirmation_msg($content, $order) {
        return $content;
    }
    
}