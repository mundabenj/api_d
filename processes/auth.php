<?php
class auth{
    public function signup($conn, $ObjGlob){
        if(isset($_POST["signup"])){

            $errors = array();

            $fullname = $_POST["fullname"];
            $email_address = $_POST["email_address"];
            $username = $_POST["username"];

// Implement input validation and error handling
// =============================================
// Sanitize all inputs
// verify that the fullname has only letters, space, dash, quotation
// verify that the email has got the correct format

if(!filter_var($email_address, FILTER_VALIDATE_EMAIL)){
    $errors['email_format_err'] = 'Wrong email format';
}

// verify that the email domain is authorized (@strathmore.edu, @gmail.com, @yahoo.com, @mada.co.ke) and not (@yanky.net)
// verify if the email alredy exists in the database
// verify if the username alredy exists in the database

// Implement 2FA (email => PHP-Mailer)
// ===================================
// Send email verification with an OTP (OTC)
// Verify that the password is identical to the repeat passsword
// verify that the password length is between 4 and 8 characters
if(!count($errors)){
            $cols = ['fullname', 'email', 'username'];
            $vals = [$fullname, $email_address, $username];
            $data = array_combine($cols, $vals);
            $insert = $conn->insert('users', $data);
            if($insert === TRUE){
                header('Location: signup.php');
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