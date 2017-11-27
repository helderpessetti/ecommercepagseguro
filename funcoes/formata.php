<?php
/**
 * Função para formatar Telefone, CEP, CPF, CNPJ e RG
 *
 * Escolher tipo de formatação ( fone, cep, cpf, cnpj ou rg) 
 * Lembrar de colocar em lowercase
 * @param $tipo  string
 *   
 * Enviar string que para ser formata ex: 13974208014;
 * @param $string  string   
 *
 * Quantidade de caracteres a serem formatados, 
 * só serve para o telefone 10 para o padrão antigo e 11 para novo padrão com 9
 * @param $size  integer  
 *
 *
 * Valor formatado do padrão escolhido
 * @return $string  string   
 */

//  R E V I S A R 

function formatar ($tipo = "", $string, $size = 10)
{
    $string = ereg_replace("[^0-9]", "", $string);
    
    switch ($tipo)
    {
        case 'fone':
            if($size === 10){
             $string = '(' . substr($tipo, 0, 2) . ') ' . substr($tipo, 2, 4) . '-' . substr($tipo, 6);
         }else
         if($size === 11){
             $string = '(' . substr($tipo, 0, 2) . ') ' . substr($tipo, 2, 5) 
             . '-' . substr($tipo, 7);
         }
         break;
        case 'cep':
            $string = substr($string, 0, 5) . '-' . substr($string, 5, 3);
         break;
        case 'cpf':
            $string = substr($string, 0, 3) . '.' . substr($string, 3, 3) . 
                '.' . substr($string, 6, 3) . '-' . substr($string, 9, 2);
         break;
        case 'cnpj':
            $string = substr($string, 0, 2) . '.' . substr($string, 2, 3) . 
                '.' . substr($string, 5, 3) . '/' . 
                substr($string, 8, 4) . '-' . substr($string, 12, 2);
         break;
        case 'rg':
            $string = substr($string, 0, 2) . '.' . substr($string, 2, 3) . 
                '.' . substr($string, 5, 3);
         break;
        default:
         $string = 'É ncessário definir um tipo (fone, cep, cpg, cnpj, rg)';
         break;
    }
    return $string;
}

?>