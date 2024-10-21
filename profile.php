<?php
require "load.php";
$ObjGlob->checksignin();
$ObjLayouts->heading();
$ObjMenus->main_menu();
$ObjHeadings->main_banner();
$ObjForm->profile_form($ObjGlob, $conn);
$ObjCont->side_bar();
$ObjLayouts->footer();