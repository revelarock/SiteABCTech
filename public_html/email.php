<?php

// inclusao do arquivo com a funcao para envio de email com autenticacao SMTP
require('includes/phpmailer/hdw-phpmailer.php');



// recebe os campos do formulario
$name			= trim($_REQUEST['name']);
$email			= trim($_REQUEST['email']);
$message		= trim($_REQUEST['message']);



// define a data e hora da mensagem
date_default_timezone_set('America/Sao_Paulo');
$datahora = date('d/m/Y H:i:s');



// define o IP de envio da mensagem
$IP = $_SERVER['REMOTE_ADDR'];



// define o assunto da mensagem
$emailAssunto = 'Contato do site';



// define o texto da mensagem em HTML
$emailMensagem = "
<strong>{$emailAssunto}</strong><br />
<hr />

<strong>Nome:</strong> {$name}<br />
<strong>Email:</strong> {$email}<br />
<strong>Mensagem:</strong><br />
{$message}<br />

<hr />
<strong>Data/Hora:</strong> {$datahora}<br />
<strong>IP:</strong> {$IP}<br />
<br />
";



// inicia configuracoes do email

// DADOS DO REMETENTE (quem envia o email)
$emailDe = array();


// informe o email do remetente
// IMPORTANTE: este email deve obrigatoriamente ser do mesmo dominio do site
$emailDe['from']		= 'site@lojaabctech.com.br';


// informe o nome do remetente
$emailDe['fromName']	= $nome; // por padrao puxa o nome preenchido no formulario


// informe o email para resposta
// pode ser informado qualquer email de qualquer dominio
$emailDe['replyTo']		= $email; // por padrao puxa o email preenchido no formulario


// informe o email de retorno em caso de erro
// IMPORTANTE: este email deve obrigatoriamente ser do mesmo dominio do site
$emailDe['returnPath']	= 'site@lojaabctech.com.br';


// informe o email para envio de confirmacao de leitura (opcional)
// deixe vazio para nao enviar confirmacao
// IMPORTANTE: este email deve obrigatoriamente ser do mesmo dominio do site
$emailDe['confirmTo']	= '';



// DADOS DO DESTINATARIO (quem ira receber o email)
$emailPara = array();


// informe ao menos um email de destinatario, o nome é opcional
// IMPORTANTE: podem ser adicionados varios destinatarios, fique atento a numeracao do array!
// #1
$emailPara[1]['to']		= 'contato@lojaabctech.com.br';
$emailPara[1]['toName']	= 'ABC Tech';
// #2
//$emailPara[2]['to']		= 'seuemail2@seudominio.com.br';
//$emailPara[2]['toName']	= 'Seu Nome2';



// DADOS DA CONTA SMTP PARA AUTENTICACAO DO ENVIO
$SMTP = array();
$SMTP['host']		= 'mail.lojaabctech.com.br';
$SMTP['port']		= 26; // para o gmail utilize 587
$SMTP['encrypt']	= ''; // ssl ou tls ou vazio, para o gmail utilize tls
$SMTP['username']	= 'site@lojaabctech.com.br'; // recomendamos criar uma conta de email somente para ser utilizada aqui
$SMTP['password']	= 'Site@123'; // pois cada vez que a senha for alterada este arquivo tambem devera ser atualizado
$SMTP['charset']	= 'utf-8'; // 'utf-8' ou 'iso-8859-1', siga o padrao do arquivo para nao haver erros na acentuacao
$SMTP['priority']	= 3; // prioridade: 1=alta; 3=normal; 5=baixa;


// DEBUG (ajuda para descobrir erros)
// - use TRUE para ver os erros de envio
// - uma vez configurado e funcionando obrigatoriamente utilize FALSE
$SMTP['debug'] = FALSE;


// faz o envio
$mail = sendEmail($emailDe, $emailPara, $emailAssunto, $emailMensagem, $SMTP);


// em caso de erro
if($mail !== TRUE){
	// redireciona ou exibe uma mensagem de erro
	//header('location: erro.html');
	echo('.<br />Erro: '.$mail);
	exit;
}


// em caso de sucesso
// redireciona ou exibe a mensagem de agradecimento
header('location: obrigado.html'); exit;

?>