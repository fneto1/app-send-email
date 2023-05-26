<?php


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require "PHPMailer/PHPMailer.php";
require "PHPMailer/Exception.php";
require "PHPMailer/SMTP.php";

class Mensagem{
    private $para;
    private $assunto;
    private $mensagem;
    public $status = Array('codigo_status'=>null, 'descricao_status'=> '');

    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    public function __get($name)
    {
        return $this->$name;
    }

    public function mensagemValida(){
        if(empty($this->para) || empty($this->assunto) || empty($this->mensagem)){
            return false;
        } else {
            return true;
        }
    }
}

$mensagem = new Mensagem();

$mensagem->__set('para', $_POST['para']);
$mensagem->__set('assunto', $_POST['assunto']);
$mensagem->__set('mensagem', $_POST['mensagem']);

if(!($mensagem->mensagemValida())){
    header('location: index.php');
}

$mail = new PHPMailer(true);

try {
    //Server settings
    //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->SMTPDebug = false;
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'fnetocivil@gmail.com';                     //SMTP username
    $mail->Password   = 'cxkyonggrofyzbml';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    $mail->CharSet = 'UTF-8';

    //Recipients
    $mail->setFrom('fnetocivil@gmail.com', 'Cobra Correios');
    $mail->addAddress($mensagem->__get('para'), 'Cobra Usuario');     //Add a recipient
    //$mail->addAddress('ellen@example.com');               //Name is optional
    //$mail->addReplyTo('info@example.com', 'Information');
    //$mail->addCC('cc@example.com');
    //$mail->addBCC('bcc@example.com');

    //Attachments
    //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = $mensagem->__get('assunto');
    $mail->Body    = $mensagem->__get('mensagem');
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    $mensagem->status['codigo_status'] = 1;
    $mensagem->status['descricao_status'] = 'Mensagem enviada!';
    //echo 'Message has been sent';
} catch (Exception $e) {
    $mensagem->status['codigo_status'] = 2;
    $mensagem->status['descricao_status'] = 'Mensagem não enviada enviada! Erro:'.$mail->ErrorInfo;
    //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

?>

<html lang="pt-br">
<head>
    <meta charset="utf-8" />
    <title>App Mail Send</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>

<div class="py-3 text-center">
    <img class="d-block mx-auto mb-2" src="logo.png" alt="" width="72" height="72">
    <h2>Send Mail</h2>
    <p class="lead">Seu app de envio de e-mails particular!</p>
</div>

<div class="row">
    <div class="col-md-12">
        <?if($mensagem->status['codigo_status'] == 1){ ?>
            <div class="container">
                <h1 class="display-4 text-success">Sucesso</h1>
                <p><?=$mensagem->status['descricao_status']?></p>
                <a href="index.php" class="btn btn-success btn-lg mt-5 text-white">Voltar</a>
            </div>
        <?}?>

        <?if($mensagem->status['codigo_status'] == 2){ ?>
            <div class="container">
                <h1 class="display-4 text-danger">Erro</h1>
                <p><?=$mensagem->status['descricao_status']?></p>
                <a href="index.php" class="btn btn-danger btn-lg mt-5 text-white">Voltar</a>
            </div>
        <?}?>

    </div>

</div>

</body>
</html>


