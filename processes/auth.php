<?php
class auth{

    public function bind_to_template($replacements, $template) {
        return preg_replace_callback('/{{(.+?)}}/', function($matches) use ($replacements) {
            return $replacements[$matches[1]];
        }, $template);
    }

    public function signup($conn, $ObjGlob, $ObjSendMail, $lang, $conf){
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
// die($spot_email_address_res);
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
}

// Verify that the password is identical to the repeat passsword
// verify that the password length is between 4 and 8 characters
if(!count($errors)){

// Implement 2FA (email => PHP-Mailer)
// ===================================
// Send email verification with an OTP (OTC)


            $cols = ['fullname', 'email', 'username', 'ver_code', 'ver_code_time'];
            $vals = [$fullname, $email_address, $username, $conf['verification_code'], $conf['ver_code_time']];
            $data = array_combine($cols, $vals);
            $insert = $conn->insert('users', $data);
            if($insert === TRUE){

                $replacements = array('fullname' => $fullname, 'email_address' =>
                $email_address, 'verification_code' => $conf['verification_code'], 'site_full_name' => strtoupper($conf['site_initials']));
        
                $ObjSendMail->SendMail([
                    'to_name' => $fullname,
                    'to_email' => $email_address,
                    'subject' => $this->bind_to_template($replacements, $lang["AccountVerification"]),
                    'message' => $this->bind_to_template($replacements, $lang["AccRegVer_template"])
                ]);
                
                header('Location: verify_code.php');
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

    public function verify_code($conn, $ObjGlob, $ObjSendMail, $lang, $conf){
        if(isset($_POST["verify_code"])){
            $errors = array();

            $ver_code = $_SESSION["ver_code"] = $conn->escape_values($_POST["ver_code"]);
            if(!is_numeric($ver_code)){
                $errors['not_numeric'] = "Invalid code format. Verification Code must contain numbers only";
            }

            if(strlen($ver_code) > 6 || strlen($ver_code) < 6){
                $errors['lenght_err'] = "Invalid code length. Verification Code must have 6 digits";
            }

            $spot_ver_code_res = $conn->count_results(sprintf("SELECT ver_code FROM users WHERE ver_code = '%d' LIMIT 1", $ver_code));

            if ($spot_ver_code_res != 1){
                $errors['ver_code_not_exist'] = "Invalid verification code";
            }else{
                $spot_ver_code_time_res = $conn->count_results(sprintf("SELECT ver_code, ver_code_time FROM users WHERE ver_code = '%d' AND ver_code_time > now() LIMIT 1", $ver_code));
                if ($spot_ver_code_time_res != 1){
                    $errors['ver_code_expired'] = "Verification code expired";
                }
            }

        if(!count($errors)){
            $_SESSION['code_verified'] = $ver_code;
            header('Location: set_password.php');
        }else{
            $ObjGlob->setMsg('msg', 'Error(s)', 'invalid');
            $ObjGlob->setMsg('errors', $errors, 'invalid');
        }
        }
    }
}