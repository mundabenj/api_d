<?php
require "load.php";
$ObjGlob->checksignin();
$ObjLayouts->heading();
$ObjMenus->main_menu();
$ObjHeadings->main_banner();
$ObjForm->user_update_form($ObjGlob, $conn);
$ObjCont->side_bar();
$ObjLayouts->footer();