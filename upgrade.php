<?php
/**
 *
 *      @module       Code2
 *      @version      2.2.16
 *      @authors      Ryan Djurovich, minor changes by Chio Maisriml, websitbaker.at, Search-Enhancement by thorn, Mode-Select by Aldus, FTAN Support, syntax highlighting and current maintenance  by Martin Hecht (mrbaseman)
 *      @copyright    (c) 2009 - 2018, Website Baker Org. e.V.
 *      @link         http://forum.websitebaker.org/index.php/topic,28581.0.html
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



$query = "show fields from `".TABLE_PREFIX."mod_code2`";

$result = $database->query ( $query );

if ($database->is_error() ) {
    $admin->print_error( $database->get_error() );
} else {
    $alter = 1;
    while ( !false == $data = $result->fetchRow( MYSQL_ASSOC ) ) {
        if ($data['Field'] == "whatis") {
            $alter = 0;
            break;
        }
    }
    if ( $alter != 0 ) {
        $thisQuery = "ALTER TABLE `".TABLE_PREFIX."mod_code2`"
            . " ADD `whatis` INT NOT NULL DEFAULT 0";
        $r = $database->query($thisQuery);
        if ( $database->is_error() ) {
            $admin->print_error( $database->get_error() );
        } else {
            $admin->print_success( "Update Table for module 'code2' with success." );
        }
    }
}
