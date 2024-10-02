<?php
class auth{

    public function bind_to_template($replacements, $template) {
        return preg_replace_callback('/{{(.+?)}}/', function($matches) use ($replacements) {
            return $replacements[$matches[1]];
        }, $template);
    }


    public function signup($conn, $ObjGlob, $ObjSendMail, $lang){
        if(isset($_POST["signup"])){

            $errors = array();

            $fullname = $_SESSION["fullname"] = $conn->escape_values(ucwords(strtolower($_POST["fullname"])));
            $email_address = $_SESSION["email_address"] = $conn->escape_values(strtolower($_POST["email_address"]));
            $username = $_SESSION["username"] = $conn->escape_values(strtolower($_POST["username"]));

// Implement input validation and error handling
// =============================================
// Sanitize all inputs

// verify that the fullname has only letters, space, dash, quotation
if(ctype_alpha(str_replace(" ", "", str_replace("\'", "", $fullname))) === FALSE){
    $errors['nameLetters_err'] = "Invalid name format: Full name must contain letters and spaces only etc " . $fullname;
}

// verify that the email has got the correct format
if(!filter_var($email_address, FILTER_VALIDATE_EMAIL)){
    $errors['email_format_err'] = 'Wrong email format';
}

// verify that the email domain is authorized (@strathmore.edu, @gmail.com, @yahoo.com, @mada.co.ke) and not (@yanky.net)
$conf['valid_domains'] = ["strathmore.edu", "gmail.com", "yahoo.com", "mada.co.ke", "outlook.com", "STRATHMORE.EDU", "GMAIL.COM", "YAHOO.COM", "MADA.CO.KE", "OUTLOOK.COM"];

$arr_email_address = explode("@", $email_address);
$spot_dom = end($arr_email_address);
$spot_user = reset($arr_email_address);

if(!in_array($spot_dom, $conf['valid_domains'])){
    $errors['mailDomain_err'] = "Invalid email address domain. Use only: " . implode(", ", $conf['valid_domains']);
}
$exist_count = 0;
// Verify Email Already Exists
$spot_email_address_res = $conn->count_results(sprintf("SELECT email FROM users WHERE email = '%s' LIMIT 1", $email_address));
if ($spot_email_address_res > $exist_count){
    $errors['mailExists_err'] = "Email Already Exists";
}

// Verify Username Already Exists
$spot_username_res = $conn->count_results(sprintf("SELECT username FROM users WHERE username = '%s' LIMIT 1", $username));
if ($spot_username_res > $exist_count){
    $errors['usernameExists_err'] = "Username Already Exists";
}

// Verify if username contain letters only
if (!ctype_alpha($username)) {
    $errors['usernameLetters_err'] = "Invalid username format. Username must contain letters only";
    $ObjGlob->setMsg('errors', $errors, 'invalid');
}

// Implement 2FA (email => PHP-Mailer)
// ===================================
// Send email verification with an OTP (OTC)

$verification_code = rand(10000,99999);

$msg['verify_code_sbj'] = 'Verify Code ICS';
$msg['verify_code_msg'] = 'We are going to use this email plug in to send a unique code <p><b>'.$verification_code.'</b></p>';

// $mailMsg = [ "To Name", "To Email", "subject",  "Message" ]
$_SESSION['mailMsg'] = [
    "to_name" => $fullname, 
    "to_email" => $email_address
 ];

 $conf['site_initials'] = "ICS 2024";

 $conf['site_url'] = "http://localhost/api_d";

if(isset($_SESSION['mailMsg'])){

if(is_array($_SESSION['mailMsg'])){

    $mailMsg = $_SESSION['mailMsg'];

    $replacements = array('fullname' => $_SESSION["mailMsg"]["to_name"], 'email_address' =>
    $_SESSION["mailMsg"]["to_email"], 'unlock_token_pass' => $verification_code, 'site_full_name' => strtoupper($conf['site_initials']));

    $ObjSendMail->SendMail($mail, [
    'to_name' => $mailMsg["to_name"],
    'to_email' => $mailMsg["to_email"],
    'subject' => $this->bind_to_template($replacements, $lang["AccountVerification"]),
    'message' => $this->bind_to_template($replacements, $lang["AccRegVer_template"])
]);

}
}


// Verify that the password is identical to the repeat passsword
// verify that the password length is between 4 and 8 characters
if(!count($errors)){
            $cols = ['fullname', 'email', 'username'];
            $vals = [$fullname, $email_address, $username];
            $data = array_combine($cols, $vals);
            $insert = $conn->insert('users', $data);
            if($insert === TRUE){
                header('Location: signup.php');
                unset($_SESSION["fullname"], $_SESSION["email_address"], $_SESSION["username"]);
                exit();
            }else{
                die($insert);
            }
        }else{
            $ObjGlob->setMsg('msg', 'Error(s)', 'invalid');
            $ObjGlob->setMsg('errors', $errors, 'invalid');
        }
    }
    }
}