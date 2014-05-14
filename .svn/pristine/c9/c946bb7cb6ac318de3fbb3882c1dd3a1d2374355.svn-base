<?php
/**
 * Representação de cartão de crédito ou débito.
 *
 * @author Phellipe Kelbert <pkelbert@gmail.com>
 */

/**
 * Representação de cartão de crédito ou débito.
 *
 * @author Phellipe Kelbert <pkelbert@gmail.com>
 */
class MP_Cielo_Cartao {
    
    /**#@+
     * Indicador de situação do código de segurança.
     * @var string
     */
    const INDICADOR_NAO_INFORMADO = '0';
    const INDICADOR_INFORMADO     = '1';
    const INDICADOR_ILEGIVEL      = '2';
    const INDICADOR_INEXISTENTE   = '9';
    /**#@-*/
    
    /**
     * Número do cartão.
     * @var string
     */
    public $numero;
    
    /**
     * Mês de validade.
     * @var int
     */
    public $mes_validade;
    
    /**
     * Ano de validade.
     * @var int
     */
    public $ano_validade;
    
    /**
     * Indicador de situação do código de segurança.
     * @var string
     */
    public $indicador;
    
    /**
     * Código de segurança.
     * @var string
     */
    public $codigo;
    
    /**
     * Gera um XML de representação do cartão.
     *
     * @return string
     */
    public function toXml() {
        require_once dirname(__FILE__) . '/../util/view.php';
        
        $mes_validade = (strlen($this->mes_validade) == 1 ? '0' : '') . $this->mes_validade;
        
        $view = new MP_Cielo_View(realpath(dirname(__FILE__) . '/../../views/mensagens/cartao.pxml'));
        $view->numero    = $this->numero;
        $view->validade  = $this->ano_validade . $mes_validade;
        $view->indicador = $this->indicador;
        $view->codigo    = $this->codigo;
        
        return $view->render();
    }
    
}