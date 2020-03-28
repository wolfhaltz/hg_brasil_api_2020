<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HG_Brasil_API_Controller extends Controller
{
    private $key = "QUAL A SUA CHAVE TOPPERSON DA HG BRASIL???"; // coloque sua key maravigold da HGBrasil aqui :)
    private $error = false;

    function __construct($key = null){
        if( !empty($key) ) $this->key = $key; // se ela não estiver vazia, atribui-se a chave recebida no construtor ;)
    }

    function request ($endpoint = '', $params = array() ){
        // capturando o endpoint:
        $uri = "https://api.hgbrasil.com/".$endpoint."?key=".$this->key."&format=debug";

        if( is_array($params) ){

            foreach($params as $key => $value){
                //se for vazio, passa pro próximo
                if(empty($value)) continue;
                // se não for vazio, concatena:
                $uri.=$key.'='.urlencode($value).'&';
            }

            $uri        = substr($uri, 0, -1);
            $response   = @file_get_contents($uri); // esse @ é usado porque caso dê erro na chamada ele vai ignorar ;) manja?
            $this->error = false;
            return json_decode($response, true);
        }else{
            $this->error = true;
            return false;
        }
    }

    // apenas para retornar o erro:
    function is_error(){
        return $this->error;
    }

    function dolar_quotation(){
        $data = $this->request('/finance/quotations');
        if( !empty($data) && is_array( $data['results']['currencies']['USD'] ) ){
            $this->error = false;
            return $data['results']['currencies']['USD'];
        }else{
            $this->error = true;
            return false;
        }
    }

}
