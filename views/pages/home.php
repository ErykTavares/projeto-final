<h2>

Abrir Chamado!
</h2>

<?php 
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    function sendEmail($email, $token){
        $mail = new PHPMailer(true);
        $url =  BASE.'chamado?token='.$token;
        $details = "Opa, seu chamado foi criado com sucesso!<br/> Utilize o link abaixo para a interação: <br/> 
        <a href='$url'>click aqui!</a> ";

            try {
                $mail->SMTPDebug = 0;                      //Enable verbose debug output
                $mail->isSMTP();                                            //Send using SMTP
                $mail->Host       = 'sandbox.smtp.mailtrap.io';                     //Set the SMTP server to send through
                $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                $mail->Username   = '4cca39329b80d1';                     //SMTP username
                $mail->Password   = 'd457b3b150bebd';                               //SMTP password          //Enable implicit TLS encryption
                $mail->Port       = 2525;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                $mail->setFrom('condriano22@gmail.com', 'Mailer');
                $mail->addAddress($email, '');    
                
                $mail->isHTML(true);        
                $mail->charSet = "UTF-8";                        //Set email format to HTML
                $mail->Subject = 'Seu chamado foi aberto!';
                $mail->Body    = $details;
                $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

                $mail->send();
                echo 'Message has been sent';
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
    }

    if(isset($_POST['action'])){
        $ask = $_POST['ask'];
        $email = $_POST['email'];
        $token = md5(uniqid());
        $sql = \MySql::startConnection()->prepare("INSERT INTO called VALUES (null,?,?,?)");
        $sql->execute(array($ask,$email,$token));
        sendEmail($email, $token);
    }
?>

<form method="post">
    <input type="email" name="email" placeholder="Digite seu e-mail">
    <br />
    <textarea name="ask" placeholder="Digite sua pergunta"></textarea>
    <br />
    <input type="submit" name="action" value="Enviar">
</form>