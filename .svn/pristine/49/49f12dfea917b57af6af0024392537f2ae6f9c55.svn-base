<?php
/**
 * Representação de forma de pagamento.
 *
 * @author Phellipe Kelbert <pkelbert@gmail.com>
 */

/**
 * Representação de forma de pagamento.
 *
 * @author Phellipe Kelbert <pkelbert@gmail.com>
 */
class MP_Cielo_Pagamento {
    
    /**#@+
     * Bandeira de cartão.
     * @var string
     */
    const VISA       = 'visa';
    const MASTERCARD = 'mastercard';
    const DINERS     = 'diners';
    const DISCOVER   = 'discover';
    const ELO        = 'elo';
    const AMEX       = 'amex';
    const JCB        = 'jcb';
    const AURA       = 'aura';
    /**#@-*/
    
    /**#@+
     * Código de produto.
     * @var string
     */
    const CREDITO_A_VISTA = '1';
    const PARCELADO_LOJA  = '2';
    const PARCELADO_ADM   = '3';
    const DEBITO          = 'A';
    /**#@-*/
    
    /**
     * Nome da bandeira utilizada.
     * @var string
     */
    public $bandeira;
    
    /**
     * Código do produto.
     * @var string
     */
    public $produto;
    
    /**
     * Número de parcelas.
     * @var int
     */
    public $parcelas;
    
    /**
     * Gera um XML de representação da loja.
     *
     * @return string
     */
    public function toXml() {
        require_once dirname(__FILE__) . '/../util/view.php';
        
        $view = new MP_Cielo_View(realpath(dirname(__FILE__) . '/../../views/mensagens/pagamento.pxml'));
        $view->bandeira = $this->bandeira;
        $view->produto  = $this->produto;
        $view->parcelas = $this->parcelas;
        
        return $view->render();
    }
    
}