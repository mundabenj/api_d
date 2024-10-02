<?php

/* Email Messages Templates */
/* Subjects Templates */
$lang["AccountVerification"] = "Welcome to {{site_full_name}}! Account Verification";

/* Body Templates */
/* Account Registration Verification */
$lang["AccRegVer_template"] = "
Hello {{fullname}},

You requested an account on {{site_full_name}}.

In order to use this account you need to <a href='" . $conf['site_url'] . "/verify?tok={{verification_code}}'>Click Here</a> to complete the registration process.

Or user unique code <p><b>{{verification_code}}</b></p>

Regards,
Systems Admin
{{site_full_name}}
";