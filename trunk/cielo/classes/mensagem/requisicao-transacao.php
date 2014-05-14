<?php
/**
 * Requisição de transação.
 *
 * @author Phellipe Kelbert <pkelbert@gmail.com>
 */

/**
 * @see MP_Cielo_Mensagem
 */
require_once 'mensagem.php';

/**
 * @see MP_Cielo_Loja
 */
require_once 'loja.php';

/**
 * @see MP_Cielo_Cartao
 */
require_once 'cartao.php';

/**
 * @see MP_Cielo_Pedido
 */
require_once 'pedido.php';

/**
 * @see MP_Cielo_Pagamento
 */
require_once 'pagamento.php';

/**
 * Requisição de transação.
 *
 * @author Phellipe Kelbert <pkelbert@gmail.com>
 */
class MP_Cielo_RequisicaoTransacao extends MP_Cielo_Mensagem {
    
    /**#@+
     * Flag de autorização.
     * @var string
     */
    const SOMENTE_AUTENTICAR            = '0';
    const AUTORIZAR_SOMENTE_AUTENTICADA = '1';
    const AUTORIZAR_SEM_AUTENTICAR      = '2';
    const AUTORIZACAO_DIRETA            = '3';
    /**#@-*/
    
    /**
     * Dados da loja.
     * @var MP_Cielo_Loja
     */
    protected $_loja;
    
    /**
     * Dados do cartão.
     * @var MP_Cielo_Cartao
     */
    protected $_cartao;
    
    /**
     * Dados do pedido.
     * @var MP_Cielo_Pedido
     */
    protected $_pedido;
    
    /**
     * Dados do pagamento.
     * @var MP_Cielo_Pagamento
     */
    protected $_pagamento;
    
    /**
     * Informações extras.
     * @var string
     */
    protected $_extras;
    
    /**
     * URL de retorno.
     * @var string
     */
    public $url_retorno;
    
    /**
     * Flag de autorização.
     * @var string
     */
    public $autorizacao;
    
    /**
     * Indica se a transação deve ser capturada.
     * @var bool
     */
    public $capturar;
    
    /**
     * Construtor.
     */
    public function __construct(MP_Cielo_Loja $loja, MP_Cielo_Cartao $cartao, MP_Cielo_Pedido $pedido, MP_Cielo_Pagamento $pagamento, $extras = '') {
        parent::__construct();
        
        $this->_loja      = $loja;
        $this->_cartao    = $cartao;
        $this->_pedido    = $pedido;
        $this->_pagamento = $pagamento;
        $this->_extras    = $extras;
    }
    
    /**
     * Gera um XML de representação da requisicao.
     *
     * @return string
     */
    public function toXml() {
        require_once dirname(__FILE__) . '/../util/view.php';
        
        $view = new MP_Cielo_View(dirname(__FILE__) . '/../../views/mensagens/requisicao-transacao.pxml');
        $view->id          = $this->_id;
        $view->versao      = $this->_versao;
        $view->loja        = $this->_loja->toXml();
        $view->cartao      = $this->_cartao->toXml();
        $view->pedido      = $this->_pedido->toXml();;
        $view->pagamento   = $this->_pagamento->toXml();
        $view->url_retorno = $this->url_retorno;
        $view->autorizar   = $this->autorizacao;
        $view->capturar    = ($this->capturar ? 'true' : 'false');
        $view->extras      = $this->_extras;
        
        return $view->render();
    }
    
}