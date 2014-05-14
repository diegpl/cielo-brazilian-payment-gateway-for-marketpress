<?php
/**
 * Representação de pedidos.
 *
 * @author Phellipe Kelbert <pkelbert@gmail.com>
 */

/**
 * Representação de pedidos.
 *
 * @author Phellipe Kelbert <pkelbert@gmail.com>
 */
class MP_Cielo_Pedido {
    
    /**#@+
     * Código de moeda utilizada.
     * @var string
     */
    const MOEDA_REAL  = '986';
    const MOEDA_DOLAR = '840';
    /**#@-*/
    
    /**
     * Número do pedido.
     * @var string
     */
    public $numero;
    
    /**
     * Valor do pedido.
     * @var float
     */
    public $valor;
    
    /**
     * Código da moeda utilizada.
     * @var string
     */
    public $moeda;
    
    /**
     * Data e hora do pedido em formato UNIX.
     * @var int
     */
    public $datahora;
    
    /**
     * Descrição do pedido.
     * @var string
     */
    public $descricao;
    
    /**
     * Gera um XML de representação do pedido.
     *
     * @return string
     */
    public function toXml() {
        require_once dirname(__FILE__) . '/../util/view.php';
        
        $view = new MP_Cielo_View(realpath(dirname(__FILE__) . '/../../views/mensagens/pedido.pxml'));
        $view->numero    = $this->numero;
        $view->valor     = (int)($this->valor * 100);
        $view->moeda     = $this->moeda;
        $view->datahora  = date('Y-m-d\TH:i:s', $this->datahora);
        $view->descricao = $this->descricao;
        
        return $view->render();
    }
    
}