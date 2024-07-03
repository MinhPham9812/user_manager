<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once 'includes/phpmailer/PHPMailer.php';
require_once 'includes/phpmailer/SMTP.php';
require_once 'includes/phpmailer/Exception.php';

    if(!defined('_INCODE')) die('Access Denied...');

    function layout($layoutName, $data = []){
        if(file_exists(_WEB_PATH_TEMPLATES. '/layout/'. $layoutName .'.php')){
            require_once _WEB_PATH_TEMPLATES. '/layout/'. $layoutName .'.php';
        }
    }

    function sendMail($to, $subject, $content){
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_OFF;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = 'minh98128@gmail.com';                     //SMTP username
            $mail->Password   = 'bivo cthr eqza koqi';                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom('minh98128@gmail.com', 'Minh Pham');
            $mail->addAddress($to);                                     //Add a recipient              
            

            
            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body    = $content;
            
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );

            return $mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }

    //Check method POST
    function isPOST(){
        if($_SERVER['REQUEST_METHOD']=='POST'){
            return true;
        }

        return false;
    }

    //Check method GET
    function isGET(){
        if($_SERVER['REQUEST_METHOD']=='GET'){
            return true;
        }

        return false;
    }

    /**
     * Get and sanitize input from GET and POST requests
     *
     * This function retrieves all parameters from GET and POST requests,
     * sanitizes them to remove special characters that can be harmful,
     * and returns an associative array with the sanitized values.
     *
     * @return array The associative array containing sanitized GET and POST parameters.
     */
    function getBody() {
        // Initialize an empty array to store sanitized GET and POST parameters
        $bodyArr = [];

        // Check if the request method is GET
        if (isGET()) {
            // Check if there are any GET parameters
            if (!empty($_GET)) {
                // Loop through each GET parameter
                foreach ($_GET as $key => $value) {
                    // Sanitize the value of each parameter to remove special characters
                    // Handle array values
                    if (is_array($value)) {
                        $bodyArr[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                    } else {
                        $bodyArr[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                    }
                }
            }
        }

        // Check if the request method is POST
        if (isPOST()) {
            // Check if there are any POST parameters
            if (!empty($_POST)) {
                // Loop through each POST parameter
                foreach ($_POST as $key => $value) {
                    // Sanitize the value of each parameter to remove special characters
                    // Handle array values
                    if (is_array($value)) {
                        $bodyArr[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                    } else {
                        $bodyArr[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                    }
                }
            }
        }

        // Return the array of sanitized GET and POST parameters
        return $bodyArr;
    }

    //check validate email
    function isEmail($email){
        $checkEmail = filter_var($email, FILTER_VALIDATE_EMAIL);
        return $checkEmail;
    }

    //check validate int
    function isNumberInt($number, $range = []){
        // $range = ['min_range' => 1, 'max_range' => 20]
        if(!empty($range)){
            $options = ['options' => $range];
            $checkNumber = filter_var($number, FILTER_VALIDATE_INT, $options);
        }else{
            $checkNumber = filter_var($number, FILTER_VALIDATE_INT);
        }

        return $checkNumber;
    }

    //create notice
    function getMsg($msg, $type='success'){
        if(!empty($msg)){
            echo '<div class="alert alert-'.$type.'">';
            echo $msg;
            echo '</div>';
        }
    }

    // redirect function
    function redirect($path = 'index.php'){
        header("Location: $path");
        exit;
    }

    // notice errors
    function form_error($fieldName, $errors, $beforeHtml ='', $afterHtml=''){
        return (!empty($errors[$fieldName]))? $beforeHtml . 
        reset($errors[$fieldName]) . $afterHtml :null;
    }
    

    //stores old data from form register
    function oldData($fieldName, $oldData){
        return (!empty($oldData[$fieldName]))?$oldData[$fieldName]:null;
    }