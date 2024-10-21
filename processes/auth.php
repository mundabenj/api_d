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

// verify that the email domain is authorized

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
                unset($_SESSION["fullname"], $_SESSION["username"]);
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

            // Verifying the code is a numeric value
            if(!is_numeric($ver_code)){
                $errors['not_numeric'] = "Invalid code format. Verification Code must contain numbers only";
            }
            
            // Verifying the code has 6 characters
            if(strlen($ver_code) > 6 || strlen($ver_code) < 6){
                $errors['lenght_err'] = "Invalid code length. Verification Code must have 6 digits";
            }

            // Verifying the code exists
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

    public function set_passphrase($conn, $ObjGlob, $ObjSendMail, $lang, $conf){
        if(isset($_POST["set_pass"])){

            $errors = array();
            $passphrase = $_SESSION["passphrase"] = $conn->escape_values($_POST["passphrase"]);
            $conf_passphrase = $_SESSION["conf_passphrase"] = $conn->escape_values($_POST["conf_passphrase"]);

            // verify that the password length is between 4 and 8 characters
            // Verify the password length limit
            if(strlen($passphrase) > 30 || strlen($passphrase) < $conf['pass_length_min_limit'] ){
                $errors['pass_length_err'] = "Invalid password length. Password must have between ".$conf['pass_length_min_limit'] ." and 30 characters.";
            }
            
            // Verify that the password is identical to the repeat passsword
            // Verify that the password and confirm password match
            if(!strcmp($passphrase, $conf_passphrase) == 0){
                $errors['conf_pass_err'] = "Passwords don't match.";
            }

            if(!count($errors)){

                // hashing the password
                $hash_pass = PASSWORD_HASH($conf_passphrase, PASSWORD_DEFAULT);

                $cols = ['password', 'ver_code', 'ver_code_time'];
                $vals = [$hash_pass, 0, $conf['ver_code_timeout']];
                $where = ['ver_code' => $_SESSION['code_verified']];

                $data = array_combine($cols, $vals);
                $insert_passphrase = $conn->update('users', $data, $where);
                if($insert_passphrase === TRUE){
                    unset($_SESSION['code_verified']);
                    header('Location: signin.php');
                }else{
                    die($insert_passphrase);
                }
            }else{
                $ObjGlob->setMsg('msg', 'Error(s)', 'invalid');
                $ObjGlob->setMsg('errors', $errors, 'invalid');
            }
        }
    }
    public function signin($conn, $ObjGlob, $ObjSendMail, $lang, $conf){
        if(isset($_POST["signin"])){

            $errors = array();
            $username = $_SESSION["username"] = $conn->escape_values(strtolower($_POST["username"]));
            $entered_password = $_SESSION["passphrase"] = $conn->escape_values($_POST["passphrase"]);
            
            // Verify Username Exists
            $signin_query = (sprintf("SELECT * FROM users WHERE username = '%s' OR email = '%s' LIMIT 1", $username, $username));

            // Counting results
            $spot_username_res = $conn->count_results($signin_query);
            if ($spot_username_res == 0){
                $errors['usernamenot_err'] = "Username does not Exists";
            }else{
                // Executing the select query & Create a session.
                $_SESSION["consort_tmp"] = $conn->select($signin_query);

                // Use the session to fetch the stored password.
                $stored_password = $_SESSION["consort_tmp"]["password"];

                // Verify the password is correct
                if(password_verify($entered_password, $stored_password)){
                    // Create the login session
                    $_SESSION["consort"] = $_SESSION["consort_tmp"];
                }else{
                    unset($_SESSION["consort_tmp"]);
                    $errors['invalid_u_p'] = "Invalid username or password"; 
                }
            }

            if(!count($errors)){
                header('Location: dashboard.php');
                exit();
            }else{
                $ObjGlob->setMsg('msg', 'Error(s)', 'invalid');
                $ObjGlob->setMsg('errors', $errors, 'invalid');
            }
        }
    }
    public function signout($conn, $ObjGlob, $ObjSendMail, $lang, $conf){
        if(isset($_GET["signout"])){
            unset($_SESSION['consort']);
            header('Location: '.  $conf['site_url']);
            exit();
        }
    }
    public function save_details($conn, $ObjGlob, $ObjSendMail, $lang, $conf){
        if(isset($_POST["save_details"])){
            $errors = array();
            $genderId = $_SESSION["genderId"] = $conn->escape_values($_POST["genderId"]);
            $roleId = $_SESSION["roleId"] = $conn->escape_values($_POST["roleId"]);

            if(empty($genderId) || empty($roleId)){
                $errors['invalid_selection'] = "Invalid selectionSomething missing"; 
            }
            if(!count($errors)){
                $cols = ['genderId', 'roleId'];
                $vals = [$genderId, $roleId];
                $where = ['userId' => $_SESSION['consort']['userId']];

                $data = array_combine($cols, $vals);
                $complete_reg = $conn->update('users', $data, $where);
                if($complete_reg === TRUE){
                    $_SESSION["consort"]["genderId"] = $genderId;
                    $_SESSION["consort"]["roleId"] = $roleId;
                    header('Location: dashboard.php');
                    exit();
                }
            }else{
                $ObjGlob->setMsg('msg', 'Error(s)', 'invalid');
                $ObjGlob->setMsg('errors', $errors, 'invalid');
            }

        }
    }
}