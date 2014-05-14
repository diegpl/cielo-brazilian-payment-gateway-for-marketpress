<?php
/**
 * Abstração de mensagens enviadas à Cielo.
 *
 * @author Phellipe Kelbert <pkelbert@gmail.com>
 */
 
/**
 * Abstração de mensagens enviadas à Cielo.
 *
 * @author Phellipe Kelbert <pkelbert@gmail.com>
 */
abstract class MP_Cielo_Mensagem {
    
    /**
     * Versão da configuração da mensagem.
     * @var string
     */
    protected $_versao = '1.2.1';
    
    /**
     * Identificador da mensagem.
     * @var string
     */
    protected $_id;
    
    /**
     * Construtor.
     */
    public function __construct() {
        $this->_id = md5(date('YmdHisu'));
    }
    
    /**
     * Envia a requisição.
     *
     * @param  string           $url    URL do WebService para onde a requisição deve ser enviada.
     * @param  string           $erro   Mensagem de erro.
     * @return SimpleXMLElement|false   Objeto XML de resposta ou FALSE caso algum erro tenha ocorrido.
     */
    public function enviar($url, &$erro) {
        
        $ch = curl_init();
        
        $certfile = realpath(dirname(__FILE__) . '/../../ssl/VeriSignClass3PublicPrimaryCertificationAuthority-G5.crt');
        $post = http_build_query(array('mensagem' => $this->toXml()));
        
        curl_setopt_array($ch, array(
            CURLOPT_URL             => $url,
            CURLOPT_FAILONERROR     => true,
            CURLOPT_CONNECTTIMEOUT  => 10,
            CURLOPT_TIMEOUT         => 40,
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_SSL_VERIFYPEER  => true,
            CURLOPT_SSL_VERIFYHOST  => 2,
            CURLOPT_CAINFO          => $certfile,
            CURLOPT_SSLVERSION      => 3,
            CURLOPT_POST            => true,
            CURLOPT_POSTFIELDS      => $post,
        ));
        
        $r = curl_exec($ch);
        
        // verifica se houve erro de comunicação
        if (!$r) {
            $erro = __('Não foi possível entrar em contato com a administradora.', 'mp-cielo');
            error_log(curl_error($ch));
            curl_close($ch);
            return false;
        }
        
        curl_close($ch);
        
        $xml = simplexml_load_string($r);
        
        // verifica se a cielo retornou erro
        if ($xml->getName() == 'erro') {
            $erro = __('Não foi possível entrar em contato com a administradora.', 'mp-cielo');
            error_log(sprintf('Código de erro Cielo: %s.', $xml->codigo));
            return false;
        }
        
        return $xml;
        
    }
    
    /**
     * Gera um XML de representação da mensagem.
     *
     * @return string
     */
    abstract public function toXml();
}