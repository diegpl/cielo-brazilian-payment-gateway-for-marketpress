<?php
/**
 * Ouvinte de eventos disparados por pedidos do MarketPress.
 *
 * @author Phellipe Kelbert <pkelbert@gmail.com>
 */

/**
 * Ouvinte de eventos disparados por pedidos do MarketPress.
 *
 * @author Phellipe Kelbert <pkelbert@gmail.com>
 */
class MP_Cielo_OrderListener {
    
    /**
     * Cancela o pagamento de uma compra.
     * 
     * @param  object $post Dados da compra.
     * @return void
     */
    public function cancel_order($post) {
        
        // nomes privados de plugins da cielo
        require_once dirname(__FILE__) . '/registerer.php';
        $registerer = new MP_Cielo_Registerer();
        $plugins = array();
        foreach ($registerer->plugins as $plugin => $info) {
            $plugins[] = $info['admin_name'];
        }
        
        // se a compra não foi paga com plugin da cielo, não há nada a fazer
        if (!in_array($post->mp_payment_info['gateway_private_name'], $plugins)) {
            return;
        }
        
        // monta uma requisição de cancelamento
        require_once dirname(__FILE__) . '/../gateway/config.php';
        require_once dirname(__FILE__) . '/../mensagem/requisicao-cancelamento.php';
        $config       = new MP_Cielo_Config();
        $loja         = new MP_Cielo_Loja();
        $loja->numero = $config->get_numero_loja();
        $loja->chave  = $config->get_chave_loja();
        $requisicao   = new MP_Cielo_RequisicaoCancelamento($loja, $post->mp_payment_info['transaction_id']);
        
        // envia a requisição à cielo e coleta a resposta da operação
        $resposta = $requisicao->enviar($config->get_url(), $erro);
        
        // se houve erro no envio ou a transação não foi autorizada, notifica o usuário
        if ($resposta === false || (string)$resposta->status != '9') {
            $msg = __('Não foi possível cancelar o pagamento junto à Cielo.', 'mp-cielo');
            echo '<div class="error fade"><p><strong>' . $msg . '</strong></p></div>';
            return;
        }
        
        // cancelamento efetuado com sucesso
        $msg = __('Pagamento cancelado com sucesso junto à Cielo.', 'mp-cielo');
        echo '<div class="updated fade"><p><strong>' . $msg . '</strong></p></div>';
        
    }
    
}