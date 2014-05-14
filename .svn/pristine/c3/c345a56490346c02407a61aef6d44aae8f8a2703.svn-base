<?php
/**
 * Manipulador de arquivos de visão.
 *
 * @author Phellipe Kelbert <pkelbert@gmail.com>
 */

class MP_Cielo_View {

    /**
     * Armazena as variáveis da visão.
     * @var array
     */
    private $vars = array();

    /**
     * Nome do arquivo de visão a ser renderizado.
     * @var string
     */
    private $file;
    
    /**
     * Construtor.
     *
     * @param string $arquivo Opcional. Nome do arquivo de visão a ser carregado.
     */
    public function __construct($file = null) {
        $this->file = $file;
    }

    /**
     * Define uma variável de visão.
     *
     * @param  string $name  Nome da variável.
     * @param  mixed  $value Valor da variável.
     * @return void
     */
    public function __set($name, $value) {
        if (is_object($value) && $value instanceof MP_Cielo_View) {
            $value = $value->render();
        }
        $this->vars[$name] = $value;
    }
    
    /**
     * Retorna uma variável de visão.
     *
     * @param  string $name Nome da variável.
     * @return mixed        Valor da variável.
     */
    public function __get($name) {
        return $this->vars[$name];
    }
    
    /**
     * Indenta um texto pelo número de espaços definidos.
     *
     * @param  int    $spaces Número de espaços a serem utilizados na indentação.
     * @param  string $text   Texto a ser indentado.
     * @return string         Texto indentado.
     */
    public function indent($spaces, $text) {
        $lines = explode("\n", $text);
        $prepend = str_repeat(' ', $spaces);
        for ($i = 0; $i < count($lines); $i++) {
            $lines[$i] = $prepend . $lines[$i];
        }
        return implode("\n", $lines);
    }
    
    /**
     * Renderiza o arquivo de visão.
     *
     * @param  string $file Opcional. Nome do arquivo de visão.
     * @return string       Conteúdo do arquivo de visão processado.
     */
    public function render($file = null) {
        if (!$file) {
            $file = $this->file;
        }

        //extract($this->vars);
        ob_start();
        include($file);
        $contents = ob_get_contents();
        ob_end_clean();
        
        return $contents;
    }
    
}