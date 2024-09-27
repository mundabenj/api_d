<?php
class user_forms{
    public function sign_up_form($ObjGlob){
?>
      <div class="row align-items-md-stretch">
        <div class="col-md-9">
          <div class="h-100 p-5 text-bg-dark rounded-3">
            <h2>Sign Up</h2>
            <?php
            print $ObjGlob->getMsg('msg');
            $err = $ObjGlob->getMsg('errors');
            ?>
            <form action="<?php print basename($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="fullname" class="form-label">Fullname:</label>
                    
                    
<input type="text" name="fullname" class="form-control form-control-lg" maxlength="50" id="fullname" placeholder="Enter your name" <?php print (isset($_SESSION["fullname"])) ? 'value="'.$_SESSION["fullname"].'"'  : ''; unset($_SESSION["fullname"]); ?> >
                    
                    
                    
                    <?php print (isset($err['nameLetters_err'])) ? "<span class='invalid'>" . $err['nameLetters_err'] . "</span>" : '' ; ?>
                </div>
                <div class="mb-3">
                    <label for="email_address" class="form-label">Email Address:</label>
                    <input type="email" name="email_address" class="form-control form-control-lg" maxlength="50" id="email_address" placeholder="Enter your email address" <?php print (isset($_SESSION["email_address"])) ? 'value="'.$_SESSION["email_address"].'"'  : ''; unset($_SESSION["email_address"]); ?> >
                    <?php print (isset($err['email_format_err'])) ? "<span class='invalid'>" . $err['email_format_err'] . "</span>" : '' ; ?>
                    <?php print (isset($err['mailExists_err'])) ? "<span class='invalid'>" . $err['mailExists_err'] . "</span>" : '' ; ?>
                    <?php print (isset($err['mailDomain_err'])) ? "<span class='invalid'>" . $err['mailDomain_err'] . "</span>" : '' ; ?>
                </div>
                <div class="mb-3">
                    <label for="username" class="form-label">Username:</label>
                    <input type="text" name="username" class="form-control form-control-lg" maxlength="50" id="username" placeholder="Enter your username" <?php print (isset($_SESSION["username"])) ? 'value="'.$_SESSION["username"].'"'  : ''; unset($_SESSION["username"]); ?> >
                    <?php print (isset($err['usernameExists_err'])) ? "<span class='invalid'>" . $err['usernameExists_err'] . "</span>" : '' ; ?>
                    <?php print (isset($err['usernameLetters_err'])) ? "<span class='invalid'>" . $err['usernameLetters_err'] . "</span>" : '' ; ?>
                </div>
                <button type="submit" name="signup" class="btn btn-primary">Submit</button>
              </form>
          </div>
        </div>
<?php
    }
}