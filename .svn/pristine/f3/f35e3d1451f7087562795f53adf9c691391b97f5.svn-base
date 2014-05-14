<?php
/**
 * Representação de loja.
 *
 * @author Phellipe Kelbert <pkelbert@gmail.com>
 */

/**
 * Representação de loja.
 *
 * @author Phellipe Kelbert <pkelbert@gmail.com>
 */
class MP_Cielo_Loja {
    
    /**
     * Número da loja junto à Cielo.
     * @var string
     */
    public $numero;
    
    /**
     * Chave de acesso fornecido pela Cielo.
     * @var float
     */
    public $chave;
    
    /**
     * Gera um XML de representação da loja.
     *
     * @return string
     */
    public function toXml() {
        require_once dirname(__FILE__) . '/../util/view.php';
        
        $view = new MP_Cielo_View(realpath(dirname(__FILE__) . '/../../views/mensagens/loja.pxml'));
        $view->numero = $this->numero;
        $view->chave  = $this->chave;
        
        return $view->render();
    }
    
}