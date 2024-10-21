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
    public function verify_code_form($ObjGlob){
?>
      <div class="row align-items-md-stretch">
        <div class="col-md-9">
          <div class="h-100 p-5 text-bg-dark rounded-3">
            <h2>Verify Code</h2>
            <?php
            print $ObjGlob->getMsg('msg');
            $err = $ObjGlob->getMsg('errors');
            ?>
            <form action="<?php print basename($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="fullname" class="form-label">Verification Code:</label>
                    <input type="number" name="ver_code" class="form-control form-control-lg" maxlength="6" min="100000" max="999999" id="ver_code" placeholder="Enter your verification code" <?php if(isset($_GET["tok"])){ print 'value="'.$_GET['tok'].'"'; }else{ print (isset($_SESSION["ver_code"])) ? 'value="'.$_SESSION["ver_code"].'"'  : ''; unset($_SESSION["ver_code"]); } ?> >
                    <?php print (isset($err['not_numeric'])) ? "<span class='invalid'>" . $err['not_numeric'] . "</span>" : '' ; ?>
                    <?php print (isset($err['lenght_err'])) ? "<span class='invalid'>" . $err['lenght_err'] . "</span>" : '' ; ?>
                    <?php print (isset($err['ver_code_not_exist'])) ? "<span class='invalid'>" . $err['ver_code_not_exist'] . "</span>" : '' ; ?>
                    <?php print (isset($err['ver_code_expired'])) ? "<span class='invalid'>" . $err['ver_code_expired'] . "</span>" : '' ; ?>
                </div>
                <button type="submit" name="verify_code" class="btn btn-primary">Verify Code</button>
            </form>
          </div>
        </div>
<?php
    }
        public function set_pass_form($ObjGlob){
?>
      <div class="row align-items-md-stretch">
        <div class="col-md-9">
          <div class="h-100 p-5 text-bg-dark rounded-3">
            <h2>Set your password</h2>
            <?php
            print $ObjGlob->getMsg('msg');
            $err = $ObjGlob->getMsg('errors');
            ?>
            <form action="<?php print basename($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="passphrase" class="form-label">Enter your password:</label>
                    <input type="password" name="passphrase" class="form-control form-control-lg" id="passphrase" placeholder="Enter your verification code" <?php print (isset($_SESSION["passphrase"])) ? 'value="'.$_SESSION["passphrase"].'"'  : ''; unset($_SESSION["passphrase"]); ?> >
                    <?php print (isset($err['pass_length_err'])) ? "<span class='invalid'>" . $err['pass_length_err'] . "</span>" : '' ; ?>
                  </div>
                  <div class="mb-3">
                    <label for="conf_passphrase" class="form-label">Confirm your password:</label>
                    <input type="password" name="conf_passphrase" class="form-control form-control-lg" id="conf_passphrase" placeholder="Enter your verification code" <?php print (isset($_SESSION["conf_passphrase"])) ? 'value="'.$_SESSION["conf_passphrase"].'"'  : ''; unset($_SESSION["conf_passphrase"]); ?> >
                    <?php print (isset($err['conf_pass_err'])) ? "<span class='invalid'>" . $err['conf_pass_err'] . "</span>" : '' ; ?>
                </div>
                <button type="submit" name="set_pass" class="btn btn-primary">Save Password</button>
            </form>
          </div>
        </div>
<?php
    }
    public function sign_in_form($ObjGlob){
      ?>
            <div class="row align-items-md-stretch">
              <div class="col-md-9">
                <div class="h-100 p-5 text-bg-dark rounded-3">
                  <h2>Sign In</h2>
                  <?php
                  print $ObjGlob->getMsg('msg');
                  $err = $ObjGlob->getMsg('errors');
                  ?>
                  <form action="<?php print basename($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
                      <div class="mb-3">
                      <label for="username" class="form-label">Enter your username/email:</label>
                          <input type="text" name="username" class="form-control form-control-lg" id="username" maxlength="50" placeholder="Enter your username/email" <?php print (isset($_SESSION["username"])) ? 'value="'.$_SESSION["username"].'"'  : ''; unset($_SESSION["username"]); ?> >
                          <?php print (isset($err['usernamenot_err'])) ? "<span class='invalid'>" . $err['usernamenot_err'] . "</span>" : '' ; ?>
                          <?php print (isset($err['invalid_u_p'])) ? "<span class='invalid'>" . $err['invalid_u_p'] . "</span>" : '' ; ?>
                        </div>
                        <div class="mb-3">
                        <label for="passphrase" class="form-label">Enter your password:</label>
                        <input type="password" name="passphrase" class="form-control form-control-lg" id="passphrase" placeholder="Enter your password" <?php print (isset($_SESSION["passphrase"])) ? 'value="'.$_SESSION["passphrase"].'"'  : ''; unset($_SESSION["passphrase"]); ?> >
                          <?php print (isset($err['invalid_u_p'])) ? "<span class='invalid'>" . $err['invalid_u_p'] . "</span>" : '' ; ?>
                        </div>
                        <button type="submit" name="signin" class="btn btn-primary">Sign In</button>
                      </form>
                    </div>
                  </div>
                  <?php
          }
          public function complete_reg_form($ObjGlob, $conn){
            ?>
  <div class="row align-items-md-stretch">
    <div class="col-md-9">
      <div class="h-100 p-5 text-bg-dark rounded-3">
        <h2>Complete Registration</h2>
        <?php
            print $ObjGlob->getMsg('msg');
            $err = $ObjGlob->getMsg('errors');
            ?>
            <form action="<?php print basename($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
              <div class="mb-3">
                <label for="genderId" class="form-label">Gender:</label>
                <select name="genderId" id="genderId" class="form-control form-control-lg" required>
                  <option value="">--Select Gender--</option>
                  <?php
                    $spot_gender_rows = $conn->select_while("SELECT * FROM gender");
                    foreach($spot_gender_rows AS $spot_gender_values){
                      ?>
                    <option value="<?php print $spot_gender_values["genderId"]; ?>"><?php print $spot_gender_values["gender"]; ?></option>
                    <?php } ?>
                  </select>
                  <?php print (isset($err['invalid_selection'])) ? "<span class='invalid'>" . $err['invalid_selection'] . "</span>" : '' ; ?>
                </div>
                <div class="mb-3">
                  <label for="roleId" class="form-label">Role:</label>
                  <select name="roleId" id="roleId" class="form-control form-control-lg"  required>
                    <option value="">--Select Role--</option>
                    <?php
                        $spot_role_rows = $conn->select_while("SELECT * FROM roles");
                        foreach($spot_role_rows AS $spot_role_values){
                          if($spot_role_values["roleId"] == 1) { continue; }
                          ?>
                        <option value="<?php print $spot_role_values["roleId"]; ?>"><?php print $spot_role_values["role"]; ?></option>
                        <?php } ?>
                      </select>
                      <?php print (isset($err['invalid_selection'])) ? "<span class='invalid'>" . $err['invalid_selection'] . "</span>" : '' ; ?>
                    </div>
                    <button type="submit" name="save_details" class="btn btn-primary">Save Details</button>
                  </form>
                </div>
              </div>
    <?php
   }          public function profile_form($ObjGlob, $conn){
            ?>
  <div class="row align-items-md-stretch">
    <div class="col-md-9">
      <div class="h-100 p-5 text-bg-dark rounded-3">
        <h2>Update Profile</h2>
        <?php
            print $ObjGlob->getMsg('msg');
            $err = $ObjGlob->getMsg('errors');

$spot_profile = $conn->select(sprintf("SELECT `users`.`userId`, `users`.`fullname`, `users`.`email`, `users`.`username`, `users`.`genderId`, `users`.`roleId`, `gender`.`gender`, `roles`.`role` FROM `users` LEFT JOIN gender USING(genderId) LEFT JOIN roles USING(roleId) WHERE `users`.`userId` = '%d' LIMIT 1", $_SESSION['consort']['userId']));

            ?>
            <form action="<?php print basename($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                    <label for="fullname" class="form-label">Fullname:</label>
					<input type="text" name="fullname" class="form-control form-control-lg" maxlength="50" id="fullname" placeholder="Enter your name" value="<?php print $spot_profile["fullname"]; ?>">
                    <?php print (isset($err['nameLetters_err'])) ? "<span class='invalid'>" . $err['nameLetters_err'] . "</span>" : '' ; ?>
                </div>
                <div class="mb-3">
                    <label for="email_address" class="form-label">Email Address:</label>
                    <input type="email" name="email_address" class="form-control form-control-lg" maxlength="50" id="email_address" placeholder="Enter your email address" value="<?php print $spot_profile["email"]; ?>">

                    <?php print (isset($err['email_format_err'])) ? "<span class='invalid'>" . $err['email_format_err'] . "</span>" : '' ; ?>
                    <?php print (isset($err['mailExists_err'])) ? "<span class='invalid'>" . $err['mailExists_err'] . "</span>" : '' ; ?>
                    <?php print (isset($err['mailDomain_err'])) ? "<span class='invalid'>" . $err['mailDomain_err'] . "</span>" : '' ; ?>
                </div>
                <div class="mb-3">
                    <label for="username" class="form-label">Username:</label>
                    <input type="text" name="username" class="form-control form-control-lg" maxlength="50" id="username" placeholder="Enter your username" value="<?php print $spot_profile["username"]; ?>" >
                    <?php print (isset($err['usernameExists_err'])) ? "<span class='invalid'>" . $err['usernameExists_err'] . "</span>" : '' ; ?>
                    <?php print (isset($err['usernameLetters_err'])) ? "<span class='invalid'>" . $err['usernameLetters_err'] . "</span>" : '' ; ?>
                </div>
              <div class="mb-3">
                <label for="genderId" class="form-label">Gender:</label>
                <select name="genderId" id="genderId" class="form-control form-control-lg" required>
                  <option value="<?php print $spot_profile["genderId"]; ?>"><?php print $spot_profile["gender"]; ?></option>
                  <?php
                    $spot_gender_rows = $conn->select_while("SELECT * FROM gender");
                    foreach($spot_gender_rows AS $spot_gender_values){
                      if($spot_gender_values["genderId"] == $spot_profile["genderId"]){continue;}
                      ?>
                    <option value="<?php print $spot_gender_values["genderId"]; ?>"><?php print $spot_gender_values["gender"]; ?></option>
                    <?php } ?>
                  </select>
                  <?php print (isset($err['invalid_selection'])) ? "<span class='invalid'>" . $err['invalid_selection'] . "</span>" : '' ; ?>
                </div>
                <div class="mb-3">
                  <label for="roleId" class="form-label">Role:</label>
                  <select name="roleId" id="roleId" class="form-control form-control-lg"  required>
                  <option value="<?php print $spot_profile["roleId"]; ?>"><?php print $spot_profile["role"]; ?></option>
                    <?php
                        $spot_role_rows = $conn->select_while("SELECT * FROM roles");
                        foreach($spot_role_rows AS $spot_role_values){
                          if($spot_role_values["roleId"] == 1 || $spot_role_values["roleId"] == $spot_profile["roleId"]) { continue; }
                          ?>
                        <option value="<?php print $spot_role_values["roleId"]; ?>"><?php print $spot_role_values["role"]; ?></option>
                        <?php } ?>
                      </select>
                      <?php print (isset($err['invalid_selection'])) ? "<span class='invalid'>" . $err['invalid_selection'] . "</span>" : '' ; ?>
                    </div>
                    <button type="submit" name="update_profile" class="btn btn-primary">Update Profile</button>
                  </form>
                </div>
              </div>
    <?php
   }
}