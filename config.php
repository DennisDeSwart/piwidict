<?php

$root=$_SERVER["DOCUMENT_ROOT"];
$site_url="/";
//$root="D:/all/projects/git/piwidict";
// $site_url="/~lalala/";

$PHP_SELF=$_SERVER["PHP_SELF"];

if (substr($root,-1,1) != "/") $root.="/";
define("SITE_ROOT",$root);
define("LIB_DIR",SITE_ROOT."lib/");

define ('NAME_DB','ruwikt20140904_parsed');
define ('WIKT_LANG','ru');
define ('INTERFACE_LANGUAGE', 'en'); 

// misc classes
/*include_once(LIB_DIR."sessione.php");
include_once(LIB_DIR."array_util.php");
include_once(LIB_DIR."string_util.php");
include_once(LIB_DIR."db/mysql_util.php");
*/

// dictionary classes
include_once(LIB_DIR."sql/DB.php");
include_once(LIB_DIR."sql/TLabel.php");
include_once(LIB_DIR."sql/TLang.php");
include_once(LIB_DIR."sql/TLangPOS.php");
include_once(LIB_DIR."sql/TMeaning.php");
include_once(LIB_DIR."sql/TPage.php");
include_once(LIB_DIR."sql/TPOS.php");
include_once(LIB_DIR."sql/TRelation.php");
include_once(LIB_DIR."sql/TRelationType.php");
include_once(LIB_DIR."sql/TTranslation.php");
include_once(LIB_DIR."sql/TTranslationEntry.php");
include_once(LIB_DIR."sql/TWikiText.php");

$LINK_DB=new DB;

foreach ($_REQUEST as $var=>$value) {
/*
TODO!!! check vars
*/
   $$var = $value;
}
/*
$NAME_DB = 'ruwikt20140904_parsed';

$config['hostname']   = 'localhost';
$config['login']      = 'javawiki';
$config['password']   = '';
$config['dbname']     = $NAME_DB;
    
*/

##------------------------------------------------------------------------------------------------------
## DB connection 
## mysql>GRANT SELECT, INSERT, UPDATE ON vepsian.* TO vepsian@'%' identified by 'W4qOfWf';
## mysql>FLUSH PRIVILEGES;
##
//function connectMySQL($config) {
/*
function connectMySQL() {
    global $config;
    // print_r ($config);
    
    $sock = mysqli_connect($config['hostname'], $config['login'], $config['password']) //, $config['db'][$db]) 
            or die ("Could not connect to MySQL server! " +
                    "or could not select database ".$config['dbname']."!");
    
    mysqli_select_db($sock, $config['dbname'])
            or die("Could not select database ".$config['dbname']."!");
    mysqli_set_charset($sock, "binary"); // utf8
    return $sock;
}
*/
?>