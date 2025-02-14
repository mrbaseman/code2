<?php
/**
 *
 *      @module       Code2
 *      @version      2.2.18
 *      @authors      Ryan Djurovich, minor changes by Chio Maisriml, websitbaker.at, Search-Enhancement by thorn, Mode-Select by Aldus, FTAN Support, syntax highlighting and current maintenance  by Martin Hecht (mrbaseman)
 *      @copyright    (c) 2009 - 2021, Website Baker Org. e.V.
 *      @link         https://github.com/mrbaseman/code2
 *      @license      GNU General Public License
 *      @platform     2.8.x
 *      @requirements PHP 5.2.x and higher
 *
 **/


/* -------------------------------------------------------- */
// Must include code to stop this file being accessed directly
if(!defined('WB_PATH')) {
        // Stop this file being access directly
        if(!headers_sent()) header("Location: ../index.php",TRUE,301);
        die('<head><title>Access denied</title></head><body><h2 style="color:red;margin:3em auto;text-align:center;">Cannot access this file directly</h2></body></html>');
}
/* -------------------------------------------------------- */


$database->query(
    "DELETE FROM `".TABLE_PREFIX."search`"
        . " WHERE `name` = 'module'"
        . " AND value = 'code2'"
    );
$database->query(
    "DELETE FROM `".TABLE_PREFIX."search`"
        . " WHERE `extra` = 'code2'"
    );
$database->query("DROP TABLE IF EXISTS ".TABLE_PREFIX."mod_code2");

$directory= WB_PATH."/temp/modules/code2" ;
$check =rm_full_dir($directory, $empty = false) ;

if ($check !== true) {echo "Das Datenverzeichniss $directory konnte nicht komplett gel&ouml;scht werden!";}

