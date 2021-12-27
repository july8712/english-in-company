<?php
    //Import PHPMailer classes into the global namespace
    //These must be at the top of your script, not inside a function
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    //Load Composer's autoloader
    include '../vendor/autoload.php';

    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    $email_from = "englishincompany0@gmail.com";

    if($_SERVER['REQUEST_METHOD'] === "POST"){
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            $address = 'http://' . $_SERVER['SERVER_NAME'];

            if (strpos($address, $_SERVER['HTTP_ORIGIN']) !== 0) {
                echo 'Invalid Origin header: ' . $_SERVER['HTTP_ORIGIN'];
            }
			
            if($_GET['form'] === "contact") {

                $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
                $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
                $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
                $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);

                if(
                    $name === false ||
                    $email === false ||
                    $phone === false ||
                    $message === false
                ) {
                    echo "error";
                    exit();
                } else {
                    try {
                        //Server settings
                        //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
                        $mail->isSMTP();                                            //Send using SMTP
                        $mail->Host       = '';                     //Set the SMTP server to send through
                        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                        $mail->Username   = '';                     //SMTP username
                        $mail->Password   = '';                               //SMTP password
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
                        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                        //Recipients
                        $mail->setFrom($email, $name);
                        $mail->addAddress($email_from);     //Add a recipient
                        $mail->addReplyTo('info@example.com', 'Information');
                        
                        $email_message = "<!DOCTYPE html><html lang=\"es\"><head><meta charset=\"utf-8\"></head><body>";
                        $email_message .= "<p>Detalles del formulario de contacto:</p><ul>";
                        $email_message .= "<li><strong>Nombre:</strong> " . $name . "</li>";
                        $email_message .= "<li><strong>E-mail:</strong> " . $email . "</li>";
                        $email_message .= "<li><strong>Celular:</strong> " . $phone . "</li>";
                        $email_message .= "<li><strong>Mensage</strong> " . $message . "</li>";
                        $email_message .= "</ul></body></html>";
                    
                        //Content
                        $mail->isHTML(true);                                  //Set email format to HTML
                        $mail->Subject = "Contacto Web";
                        $mail->Body    = $email_message;
                        
                        $mail->send();
                        echo "ok";
                    } catch (Exception $e) {
                        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                    }
                } 
            } else {
                echo 'No Origin header';
            }    
        }
    }