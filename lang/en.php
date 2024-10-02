<?php
 $conf['site_url'] = "http://localhost/api_d";
/* Email Messages Templates */
/* Subjects Templates */
$lang["AccountVerification"] = "Welcome to {{site_full_name}}! Account Verification";

/* Body Templates */
// Account Registration Verification
$lang["AccRegVer_template"] = "
Hello {{fullname}},

You requested an account on {{site_full_name}}.

In order to use this account you need to <a href='" . $conf['site_url'] . "/ges/verify?tok={{unlock_token_pass}}'>Click Here</a> to complete the registration process.

Or user unique code <p><b>{{unlock_token_pass}}</b></p>

Regards,
Systems Admin
{{site_full_name}}
";