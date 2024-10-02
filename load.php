<?php
session_start();
require "includes/constants.php";
require "includes/dbConnection.php";
require "lang/en.php";

// Class Auto Load 

function classAutoLoad($classname){

    $directories = ["contents", "layouts", "menus", "forms", "processes", "global"];

    foreach($directories AS $dir){
        $filename = dirname(__FILE__) . DIRECTORY_SEPARATOR . $dir . DIRECTORY_SEPARATOR . $classname . ".php";
        if(file_exists($filename) AND is_readable($filename)){
            require_once $filename;
        }
    }
}

spl_autoload_register('classAutoLoad');


    $ObjGlob = new fncs();
    $ObjSendMail = new SendMail();

// Create instances of all classes
    $ObjLayouts = new layouts();
    $ObjMenus = new menus();
    $ObjHeadings = new headings();
    $ObjCont = new contents();
    $ObjForm = new user_forms();
    $conn = new dbConnection(DBTYPE, HOSTNAME, DBPORT, HOSTUSER, HOSTPASS, DBNAME);


// Create process instances

    $ObjAuth = new auth();
    $ObjAuth->signup($conn, $ObjGlob, $ObjSendMail, $lang);

