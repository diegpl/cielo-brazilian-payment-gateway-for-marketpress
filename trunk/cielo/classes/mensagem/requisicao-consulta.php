<?php
/**
 * Requisição de consulta.
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
 * Requisição de consulta.
 *
 * @author Phellipe Kelbert <pkelbert@gmail.com>
 */
class MP_Cielo_RequisicaoConsulta extends MP_Cielo_Mensagem {
    
    /**
     * Dados da loja.
     * @var MP_Cielo_Loja
     */
    protected $_loja;
    
    /**
     * Identificador da transação.
     * @var string
     */
    protected $_tid;
    
    /**
     * Construtor.
     */
    public function __construct(MP_Cielo_Loja $loja, $tid) {
        parent::__construct();
        
        $this->_loja = $loja;
        $this->_tid  = $tid;
    }
    
    /**
     * Gera um XML de representação da requisicao.
     *
     * @return string
     */
    public function toXml() {
        require_once dirname(__FILE__) . '/../util/view.php';
        
        $view = new MP_Cielo_View(dirname(__FILE__) . '/../../views/mensagens/requisicao-consulta.pxml');
        $view->id          = $this->_id;
        $view->versao      = $this->_versao;
        $view->loja        = $this->_loja->toXml();
        $view->tid         = $this->_tid;
        
        return $view->render();
    }
    
}