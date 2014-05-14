<?php
/**
 * Abstração de plugins de gateway de pagamento via cartão de débito pela Cielo para MarketPress.
 * 
 * @author Phellipe Kelbert <pkelbert@gmail.com>
 */

/**
 * @see MP_Cielo_Gateway
 */
require_once 'cielo.php';

/**
 * Abstração de plugins de gateway de pagamento via cartão de débito pela Cielo.
 * 
 * @author Phellipe Kelbert <pkelbert@gmail.com>
 */
abstract class MP_Cielo_Gateway_Debito extends MP_Cielo_Gateway {
    
    /**
     * Imprime o formulário de configurações do plugin.
     *
     * @param  array $settings Configurações salvas do MarketPress.
     * @return void
     */
    public function gateway_settings_box($settings) {
        global $wp_filters, $mp;
        
        parent::gateway_settings_box($settings);
        
        require_once dirname(__FILE__) . '/../util/view.php';
        
        $view = new MP_Cielo_View(dirname(__FILE__) . '/../../views/settings/debito.phtml');
        
        $view->plugin_name = $this->plugin_name;
        $view->admin_name = $this->admin_name;
        $view->settings = $settings;
        
        echo $view->render();
    }
    
    /**
     * Exibe campos necessários para processar o pagamento.
     *
     * @param  array  $cart          Conteúdo do carrinho de compras.
     * @param  array  $shipping_info Informações de entrega e email.
     * @return string                Conteúdo HTML do formulário.
     */
    public function payment_form($cart, $shipping_info) {
        global $mp;
        
        $result = parent::payment_form($cart, $shipping_info);
        
        require_once dirname(__FILE__) . '/../util/view.php';
        $view = new MP_Cielo_View(dirname(__FILE__) . '/../../views/payment-form/debito.phtml');
        $view->gateway = $this;
        $view->bradesco_img_url = $mp->plugin_url . 'plugins-gateway/cielo/imagens/bradesco.gif';
        
        $campos = array('numero', 'mes_validade', 'ano_validade', 'codigo');
        foreach ($campos as $campo) {
            $view->$campo = (isset($_POST[$this->plugin_name][$campo]) ? $_POST[$this->plugin_name][$campo] : '');
        }
        
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
            'numero'       => 'É necessário informar o número do cartão.',
            'validade'     => array(
                array('mes_validade', 'ano_validade'),
                'É necessário informar a validade do cartão.',
            ),
            'codigo'       => 'É necessário informar o código de segurança do cartão.',
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
        
        $view = new MP_Cielo_View(dirname(__FILE__) . '/../../views/confirm-payment/debito.phtml');
        $view->gateway = $this;
        $view->numero  = $_SESSION[$this->plugin_name]['numero'];
        
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
        
        $order_id = $mp->generate_order_id();
        
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
        $pedido->numero    = $order_id;
        $pedido->valor     = $this->_total_compra($cart);
        $pedido->moeda     = $this->_moeda;
        $pedido->datahora  = time();
        $pedido->descricao = 'Origem: ' . $_SERVER['REMOTE_ADDR'];
        
        // dados do pagamento
        $pagamento = new MP_Cielo_Pagamento();
        $pagamento->bandeira = $this->_bandeira;
        $pagamento->parcelas = 1;
        $pagamento->produto  = MP_Cielo_Pagamento::DEBITO;
        
        // monta a requisição de transação
        $requisicao = new MP_Cielo_RequisicaoTransacao($loja, $cartao, $pedido, $pagamento);
        $requisicao->url_retorno = $this->ipn_url . '?order_id=' . $order_id;
        $requisicao->autorizacao = MP_Cielo_RequisicaoTransacao::AUTORIZAR_SOMENTE_AUTENTICADA;
        $requisicao->capturar    = true;
        
        // envia a requisição à cielo e coleta a resposta da operação
        $resposta = $requisicao->enviar($this->_ws_url, $erro);
        
        // verifica se houve erro no envio
        if ($resposta === false) {
            $mp->cart_checkout_error($erro);
            return;
        }
        
        // define dados do pagamento
        $payment_info = array(
            'status'                => array(
                $pedido->datahora   => __('Pedido confirmado; solicitação de pagamento enviada à Cielo.', 'mp-cielo'),
            ),
            'gateway_public_name'   => $this->public_name,
            'gateway_private_name'  => $this->admin_name,
            'method'                => __('À vista', 'mp-cielo'),
            'transaction_id'        => (string)$resposta->tid,
            'total'                 => $pedido->valor,
            'currency'              => $this->_simbolo_moeda($this->_moeda),
        );
        
        // armazena dados temporários enquanto confirmação de pagamento não é feita
        $timeout = 12 * 60 * 60; // 12 horas
        set_transient('mp_order_'. $order_id . '_cart', $cart, $timeout);
		set_transient('mp_order_'. $order_id . '_shipping', $shipping_info, $timeout);
		set_transient('mp_order_'. $order_id . '_payment', $payment_info, $timeout);
        
        // redireciona para a url de autenticação
        wp_redirect((string)$resposta->{'url-autenticacao'});
        exit;
    }
    
    /**
     * Utilizado para manipular retorno de autenticação.
     *
     * @return void
     */
    public function process_ipn_return() {
        
        // recupera informações da compra
        $order_id      = $_GET['order_id'];
        $cart          = get_transient('mp_order_' . $order_id . '_cart');
        $shipping_info = get_transient('mp_order_' . $order_id . '_shipping');
        $payment_info  = get_transient('mp_order_' . $order_id . '_payment');
        
        // monta uma requisição de consulta
        require_once dirname(__FILE__) . '/../mensagem/requisicao-consulta.php';
        $loja = new MP_Cielo_Loja();
        $loja->numero = $this->_ec_numero;
        $loja->chave  = $this->_ec_chave;
        $requisicao = new MP_Cielo_RequisicaoConsulta($loja, $payment_info['transaction_id']);
        
        // envia a requisição à cielo e coleta a resposta da operação
        $resposta = $requisicao->enviar($this->_ws_url, $erro);
        
        // se houve erro no envio ou a transação não foi autorizada, retorna à página de confirmação do pedido
        if ($resposta === false || (string)$resposta->status != '6') {
            $erro = ($erro != '' ? $erro : __('Transação não autorizada pela administradora do cartão.', 'mp-cielo'));
            set_transient('mp_cielo_error', $erro, 30*60);
            wp_safe_redirect(mp_checkout_step_url('confirm-checkout'));
            exit;
        }
        
        // insere status de confirmação
        $payment_info['status'][time()] = __('Pagamento confirmado pela Cielo.', 'mp-cielo');
        
        // cria o pedido
        $mp->create_order($order_id, $cart, $shipping_info, $payment_info, true);
        
        // remove informações de compra
        delete_transient('mp_order_' . $order_id . '_cart');
        delete_transient('mp_order_' . $order_id . '_shipping');
        delete_transient('mp_order_' . $order_id . '_payment');
        
        // redireciona para a página de confirmação
        wp_safe_redirect(mp_checkout_step_url('confirmation'));
        exit;
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