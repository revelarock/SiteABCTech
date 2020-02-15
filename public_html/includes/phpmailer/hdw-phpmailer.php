<?php
/*
FUNCAO PARA ENVIO DE EMAIL VIA STMP AUTENTICADO
Hotel da WEB
www.hoteldaweb.com.br
*/

/*
$SMTP = array();
$SMTP['host']		= 'mail.hoteldaweb.com.br';
$SMTP['port']		= 26; // para o gmail utilize 587
$SMTP['encrypt']	= ''; // ssl ou tls ou vazio, para o gmail utilize tls
$SMTP['username']	= 'desenvolvimento@hoteldaweb.com.br';
$SMTP['password']	= '123456';

$emailDe = array();
// informe o email do remetente
$emailDe['from']		= 'desenvolvimento@hoteldaweb.com.br';
// informe o nome do remetente
$emailDe['fromName']	= 'Hotel da WEB';
// informe o email para resposta
$emailDe['replyTo']		= 'desenvolvimento@hoteldaweb.com.br';
// informe o email de retorno em caso de erro
$emailDe['returnPath']	= 'desenvolvimento@hoteldaweb.com.br';
// informe o email para envio de confirmacao de leitura
// deixe vazio para nao enviar confirmacao
$emailDe['confirmTo']	= '';

$emailPara = array();
// informe o email do destinatario
$emailPara['to']		= 'desenvolvimento@hoteldaweb.com.br';
// informe o nome do destinatario
$emailPara['toName']	= 'Hotel da WEB';
*/

function sendEmail($emailDe, $emailPara, $assunto, $mensagem, $SMTP = FALSE){
	//SMTP needs accurate times, and the PHP time zone MUST be set
	//This should be done in your php.ini, but this is how to do it if you don't have access to that
	date_default_timezone_set('America/Sao_Paulo');
	
	require_once('class.phpmailer.php');
	
	//Create a new PHPMailer instance
	$mail = new PHPMailer();
	
	if($SMTP === FALSE){
		$SMTP = array();
		$SMTP['host'] = '';
		$SMTP['priority'] = 3;
		$SMTP['charset'] = 'iso-8859-1';
	}else{
		if(!is_array($SMTP)){ $SMTP = array( 'host' => trim($SMTP) ); }
		$SMTP['host'] = isset($SMTP['host']) ? trim($SMTP['host']) : '';
		$SMTP['port'] = isset($SMTP['port']) ? trim($SMTP['port']) : 26;
		$SMTP['encrypt'] = isset($SMTP['encrypt']) ? trim($SMTP['encrypt']) : '';
		$SMTP['username'] = isset($SMTP['username']) ? trim($SMTP['username']) : '';
		$SMTP['password'] = isset($SMTP['password']) ? trim($SMTP['password']) : '';
		$SMTP['priority'] = isset($SMTP['priority']) ?trim( $SMTP['priority']) : 3;
		$SMTP['charset'] = isset($SMTP['charset']) ? trim($SMTP['charset']) : 'iso-8859-1';
		$SMTP['debug'] = isset($SMTP['debug']) ? trim($SMTP['debug']) : 0;
	}
	
	$priority = trim($SMTP['priority']);
	if(!preg_match('/^[135]$/',$priority)){ $priority = 3; }
	
	$charset = $SMTP['charset'];
	if($charset == ''){ $charset = 'iso-8859-1'; }
	
	$mail->WordWrap = 50;
	$mail->LE = "\n";
	$mail->Priority = $priority; // 1=high; 3=normal; 5=low;
	$mail->CharSet = $charset; // default iso-8859-1
	
	if($SMTP['host'] == ''){
		//Tell PHPMailer to use php mail function
		$mail->IsMail();
	}else{
		//Tell PHPMailer to use SMTP
		$mail->IsSMTP();
		//Enable SMTP debugging
		// 0 = off (for production use)
		// 1 = client messages
		// 2 = client and server messages
		$mail->SMTPDebug  = $SMTP['debug'];
		//Ask for HTML-friendly debug output
		$mail->Debugoutput = 'html';
		
		//Whether to use SMTP authentication
		$mail->SMTPAuth   = true;
		//Set the hostname of the mail server
		$mail->Host       = $SMTP['host'];
		//Set the SMTP port number - likely to be 25, 465 or 587
		$mail->Port       = $SMTP['port'];
		//Username to use for SMTP authentication
		$mail->Username   = $SMTP['username'];
		//Password to use for SMTP authentication
		$mail->Password   = $SMTP['password'];
		//Set the encryption system to use - ssl (deprecated) or tls
		if($SMTP['encrypt'] != ''){
			$mail->SMTPSecure = $SMTP['encrypt'];
		}
	}
	
	if(!is_array($emailDe)){
		$emailDe = array( 'from' => trim($emailDe) );
		$emailDe['fromName']	= '';
		$emailDe['replyTo']		= $emailDe['from'];
		$emailDe['returnPath']	= $emailDe['from'];
		$emailDe['confirmTo']	= '';
	}else{
		$emailDe['from']		= isset($emailDe['from']) ? trim($emailDe['from']) : '';
		$emailDe['fromName']	= isset($emailDe['fromName']) ? trim($emailDe['fromName']) : '';
		$emailDe['replyTo']		= isset($emailDe['replyTo']) ? trim($emailDe['replyTo']) : $emailDe['from'];
		$emailDe['returnPath']	= isset($emailDe['returnPath']) ? trim($emailDe['returnPath']) : $emailDe['from'];
		$emailDe['confirmTo']	= isset($emailDe['confirmTo']) ? trim($emailDe['confirmTo']) : '';
	}
	
	if($emailDe['from'] == ''){
		return FALSE;
	}
	
	//Set who the message is to be sent from
	if($emailDe['fromName'] != ''){
		$mail->SetFrom($emailDe['from'], $emailDe['fromName']);
	}else{
		$mail->SetFrom($emailDe['from']);
	}
	
	//Set the return-path
	if($emailDe['returnPath'] != ''){
		$mail->Sender = $emailDe['returnPath'];
	}
	
	//Reading confirmation will be sent to
	if($emailDe['confirmTo'] != ''){
		$mail->ConfirmReadingTo = $emailDe['confirmTo'];
	}
	
	//Set an alternative reply-to address
	$mail->AddReplyTo($emailDe['replyTo']);
	
	if(!is_array($emailPara)){
		$emailPara = array(
			0 => array(
				'to' => trim($emailPara),
				'toName' => ''
			)
		);
	}else{
		if(isset($emailPara['to'])){
			$emailPara['to']		= isset($emailPara['to']) ? trim($emailPara['to']) : '';
			$emailPara['toName']	= isset($emailPara['toName']) ? trim($emailPara['toName']) : '';
			$emailPara = array(0 => $emailPara);
		}else{
			$arrTmp = array();
			foreach($emailPara as $k=>$v){
				if(!is_array($v)){
					$v = array( 'to' => trim($v) );
					$v['toName']	= '';
				}else{
					$v['to']		= isset($v['to']) ? trim($v['to']) : '';
					$v['toName']	= isset($v['toName']) ? trim($v['toName']) : '';
				}
				$arrTmp[] = $v;
			}
			$emailPara = $arrTmp;
		}
	}
	
	//Set who the message is to be sent to
	foreach($emailPara as $eP){
		if($eP['toName'] != ''){
			$mail->AddAddress($eP['to'], $eP['toName']);
		}else{
			$mail->AddAddress($eP['to']);
		}
	}
	
	//Set the subject line
	$mail->Subject = $assunto;
	//Read an HTML message body from an external file, convert referenced images to embedded, convert HTML into a basic plain-text alternative body
	$mail->MsgHTML($mensagem);
	//Replace the plain text body with one created manually
	//$mail->AltBody = 'This is a plain-text message body';
	//Attach an image file
	//$mail->AddAttachment('images/phpmailer-mini.gif');
	
	//Send the message, check for errors
	if(!$mail->Send()){
		return $mail->ErrorInfo;
	}
	
	return TRUE;
}