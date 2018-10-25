<?php

    // ------------- BEGIN OF CUSTOMIZABLE INFO ------------------

    // email of the person receiving the contact form (your email)
    $to              = "ech.chebaby@gmail.com";
    
    // your site url (for info in the email)
    $site_url        = "echebaby.com";
    
    $danger          = 'danger';
    $success         = 'success';
    $missing_name    = "Please provide your name";
    $missing_from    = "Please provide an email address";
    $invalid_from    = "Please provide a valid email address";
    $missing_message = "Please insert some text in the message";
    $could_not_send  = "There was a problem while sending the email. Please try again a bit later.";
    $mail_sent       = "Your message has been successfully sent. I will send you a reply as soon as possible. Thank you ";
    
    // ------------- END OF CUSTOMIZABLE INFO ------------------
    
    $data = array('type' => '', 'message' => '');

    function cleanEmail($email) {

        return trim(strip_tags($email));
    }

    function validEmail($email) {

        $pattern = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i";
        return preg_match($pattern, cleanEmail($email));
    }

    function verifyName($name) {

        if(empty($name)) { 

            $data['type']    = $danger;
            $data['message'] = $missing_name;

            return false;
        }

        return true;
    }

    function verifyFrom($from) {

        if(empty($from)) {

            $data['type']    = $danger;
            $data['message'] = $missing_from;

            return false;
        }

        if(!validEmail($from)) {

            $data['type']    = $danger;
            $data['message'] = $invalid_from;

            return false;
        }

        return true;
    }

    function verifyMessage($message) {

        if(empty($message)) {

            $data['type']    = $danger;
            $data['message'] = $missing_message;

            return false;
        }

        return true;
    }

    /* AJAX check  */
    if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

        $name    = trim($_POST['name']);
        $from    = trim($_POST['email']);
        $message = trim($_POST['message']);

        if (verifyFrom($from) && verifyMessage($message) && verifyName($name)) {

            $cleanFrom  = cleanEmail($from);
            $subject    = $site_url . ' - Contact';
            
            $headers    = "From: " . $name . "<" . $cleanFrom . ">" . "\r\n";
            $headers   .= "Reply-To: " . $name . "<" . $cleanFrom . ">" . "\r\n";
            $headers   .= "MIME-Version: 1.0\r\n";
            $headers   .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

            if (mail($to, $subject, $message, $headers)) {

                $data['type']    = $success;
                $data['message'] = $mail_sent;

                echo json_encode($data);

            } else {

                $data['type']    = $danger;
                $data['message'] = $could_not_send;

                echo json_encode($data);
            }

        } else {

            echo json_encode($data);
        }
    }

?>
