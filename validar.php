<?php 

use \Hcode\Model\User;
use \Hcode\Model\Cart;


function validar_cnpj($cnpj)
{
	$cnpj = preg_replace('/[^0-9]/', '', (string) $cnpj);
	// Valida tamanho
	if (strlen($cnpj) != 14)
		return false;
	// Valida primeiro dígito verificador
	for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++)
	{
		$soma += $cnpj{$i} * $j;
		$j = ($j == 2) ? 9 : $j - 1;
	}
	$resto = $soma % 11;
	if ($cnpj{12} != ($resto < 2 ? 0 : 11 - $resto))
		return false;
	// Valida segundo dígito verificador
	for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++)
	{
		$soma += $cnpj{$i} * $j;
		$j = ($j == 2) ? 9 : $j - 1;
	}
	$resto = $soma % 11;
	return $cnpj{13} == ($resto < 2 ? 0 : 11 - $resto);
}
var_dump(validar_cnpj('11.444.777/0001-61'));

/**
 * Valida CNPJ
 *
 * @author Luiz Otávio Miranda <contato@todoespacoonline.com/w>
 * @param string $cnpj 
 * @return bool true para CNPJ correto
 *
 */
function valida_cnpj ( $cnpj ) {
    // Deixa o CNPJ com apenas números
    $cnpj = preg_replace( '/[^0-9]/', '', $cnpj );
    
    // Garante que o CNPJ é uma string
    $cnpj = (string)$cnpj;
    
    // O valor original
    $cnpj_original = $cnpj;
    
    // Captura os primeiros 12 números do CNPJ
    $primeiros_numeros_cnpj = substr( $cnpj, 0, 12 );
    
    /**
     * Multiplicação do CNPJ
     *
     * @param string $cnpj Os digitos do CNPJ
     * @param int $posicoes A posição que vai iniciar a regressão
     * @return int O
     *
     */
    if ( ! function_exists('multiplica_cnpj') ) {
        function multiplica_cnpj( $cnpj, $posicao = 5 ) {
            // Variável para o cálculo
            $calculo = 0;
            
            // Laço para percorrer os item do cnpj
            for ( $i = 0; $i < strlen( $cnpj ); $i++ ) {
                // Cálculo mais posição do CNPJ * a posição
                $calculo = $calculo + ( $cnpj[$i] * $posicao );
                
                // Decrementa a posição a cada volta do laço
                $posicao--;
                
                // Se a posição for menor que 2, ela se torna 9
                if ( $posicao < 2 ) {
                    $posicao = 9;
                }
            }
            // Retorna o cálculo
            return $calculo;
        }
    }
    
    // Faz o primeiro cálculo
    $primeiro_calculo = multiplica_cnpj( $primeiros_numeros_cnpj );
    
    // Se o resto da divisão entre o primeiro cálculo e 11 for menor que 2, o primeiro
    // Dígito é zero (0), caso contrário é 11 - o resto da divisão entre o cálculo e 11
    $primeiro_digito = ( $primeiro_calculo % 11 ) < 2 ? 0 :  11 - ( $primeiro_calculo % 11 );
    
    // Concatena o primeiro dígito nos 12 primeiros números do CNPJ
    // Agora temos 13 números aqui
    $primeiros_numeros_cnpj .= $primeiro_digito;
 
    // O segundo cálculo é a mesma coisa do primeiro, porém, começa na posição 6
    $segundo_calculo = multiplica_cnpj( $primeiros_numeros_cnpj, 6 );
    $segundo_digito = ( $segundo_calculo % 11 ) < 2 ? 0 :  11 - ( $segundo_calculo % 11 );
    
    // Concatena o segundo dígito ao CNPJ
    $cnpj = $primeiros_numeros_cnpj . $segundo_digito;
    
    // Verifica se o CNPJ gerado é idêntico ao enviado
    if ( $cnpj === $cnpj_original ) {
        return true;
    }
}

function valida_cpf($cpf){
  // determina um valor inicial para o digito $d1 e $d2
  // pra manter o respeito ;)
	$d1 = 0;
	$d2 = 0;
  // remove tudo que não seja número
  $cpf = preg_replace("/[^0-9]/", "", $cpf);
  // lista de cpf inválidos que serão ignorados
  $ignore_list = array(
    '00000000000',
    '01234567890',
    '11111111111',
    '22222222222',
    '33333333333',
    '44444444444',
    '55555555555',
    '66666666666',
    '77777777777',
    '88888888888',
    '99999999999'
  );
  // se o tamanho da string for dirente de 11 ou estiver
  // na lista de cpf ignorados já retorna false
  if(strlen($cpf) != 11 || in_array($cpf, $ignore_list)){
      return false;
  } else {
    // inicia o processo para achar o primeiro
    // número verificador usando os primeiros 9 dígitos
    for($i = 0; $i < 9; $i++){
      // inicialmente $d1 vale zero e é somando.
      // O loop passa por todos os 9 dígitos iniciais
      $d1 += $cpf[$i] * (10 - $i);
    }
    // acha o resto da divisão da soma acima por 11
    $r1 = $d1 % 11;
    // se $r1 maior que 1 retorna 11 menos $r1 se não
    // retona o valor zero para $d1
    $d1 = ($r1 > 1) ? (11 - $r1) : 0;
    // inicia o processo para achar o segundo
    // número verificador usando os primeiros 9 dígitos
    for($i = 0; $i < 9; $i++) {
      // inicialmente $d2 vale zero e é somando.
      // O loop passa por todos os 9 dígitos iniciais
      $d2 += $cpf[$i] * (11 - $i);
    }
    // $r2 será o resto da soma do cpf mais $d1 vezes 2
    // dividido por 11
    $r2 = ($d2 + ($d1 * 2)) % 11;
    // se $r2 mair que 1 retorna 11 menos $r2 se não
    // retorna o valor zero a para $d2
    $d2 = ($r2 > 1) ? (11 - $r2) : 0;
    // retona true se os dois últimos dígitos do cpf
    // forem igual a concatenação de $d1 e $d2 e se não
    // deve retornar false.
    return (substr($cpf, -2) == $d1 . $d2) ? true : false;
  }
}

/*
* Verifica Cep
* Descricao: Verifica se um CEP eh Valido
* Autor: Tonho
* Contato: tonhocdn@gmail.com
* Data: 01/02/2010
* Modificacao: 01/02/2011
* Versao: 1.0.0.0
* Licenca: Copyright (C) 2011
*/
 
// verifica se um esta esta de escrito de forma correta
function validarCep($cep) {
    // retira espacos em branco
    $cep = trim($cep);
    // expressao regular para avaliar o cep
    $avaliaCep = ereg("^[0-9]{5}-[0-9]{3}$", $cep);
    
    // verifica o resultado
    if(!$avaliaCep) {            
        echo "CEP nao Valido";
    }
    else
    {
        echo "CEP Valido";
    }
}

function validaCep_01($cep){
	// VALIDAR CEP (XXXXX-XXX)
	$cep = "17052-150";
	//Expressão regular
	if (!eregi("^[0-9]{5}-[0-9]{3}$", $cep)) {
	echo "CEP inválido";
	}
}
	

function vallidarEmail($email) {
	$email = "faelcalves@hotmail.com";
	//Expressão regular no PHP
	if (!eregi("^[a-z0-9_\.\-]+@[a-z0-9_\.\-]*[a-z0-9_\-]+\.[a-z]{2,4}$", $email))
	{
		//echo "Email inválido";
		return false;
	}
	

function validaDataBrail($data) {
	// VALIDAR DATA NO FORMATO DD/MM/AAAA
	$data = "22/01/1991";
	//Expressão regular
	if (!eregi("^[0-9]{2}/[0-9]{2}/[0-9]{4}$", $data)) {
	echo "Data em formato inválido.";
	}
}
	
}

function validaFelefone_01($numero){
	// VALIDAR TELEFONE NO SEGUINTE FORMATO: DDD33333333
	$telefone = "01432363810";

	if (!eregi("^[0-9]{11}$", $telefone)) 
	{
		echo "Telefone inválido";
	}
}


function validaFelefone_02($numero){
	// VALIDAR TELEFONE NO SEGUINTE FORMATO: 3333-3333
	$telefone = "3236-3810";

	if (!eregi("^[0-9]{4}-[0-9]{4}$", $telefone)) 
	{
		echo "Telefone inválido";
	}
}


function validaFelefone_03($numero){
	// VALIDAR TELEFONE NO SEGUINTE FORMATO: (DDD) 3333-3333
	$telefone = "(014) 3236-3810";

	if (!eregi("^\([0-9]{3}\) [0-9]{4}-[0-9]{4}$", $telefone)) 
	{
		echo "Telefone inválido";
	}
	
function validaIp($ip = true){
	//$ip = "189.18.125.183";
 
	if (!eregi("^([0-9]){1,3}.([0-9]){1,3}.([0-9]){1,3}.([0-9]){1,3}$", $ip)) 
	{
		echo "IP Inválido";
		$ip = false;
	}
	return $ip;
}

function validaUrl($url = true)
{
	//$url = "http://rafaelcouto.com.br";
 
	if (!preg_match("|^http(s)?://[a-z0-9-]+(\.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i", $url)) {
	//echo "URL inválida";
		$url =false;
	}
	return $url;
}
	
	
}



 ?>