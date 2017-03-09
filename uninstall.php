<?php
/**
 *
 *        @module       Code2
 *        @version      2.2.10
 *        @authors      Ryan Djurovich, minor changes by Chio Maisriml, websitbaker.at, Search-Enhancement by thorn, Mode-Select by Aldus, FTAN Support and syntax highlighting by Martin Hecht (mrbaseman) 
 *        @copyright    (c) 2009 - 2017, Website Baker Org. e.V.
 *      @link         http://forum.websitebaker.org/index.php/topic,28581.0.html
 *        @license      GNU General Public License
 *        @platform     2.8.x
 *        @requirements PHP 5.2.x and higher
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
