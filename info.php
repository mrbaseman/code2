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


// for changelog see file CHANGELOG

$module_directory   = 'code2';
$module_name        = 'Code2';
$module_function    = 'page';
$module_version     = '2.2.18';
$module_platform    = '2.8.x';
$module_author      = 'Ryan Djurovich, minor changes by Chio Maisriml, websitbaker.at, Search-Enhancement by thorn, Mode-Select by Aldus, FTAN Support, syntax highlighting and current maintenance  by Martin Hecht (mrbaseman)';
$module_license     = 'GNU General Public License';
$module_description = 'This module allows you to execute PHP, HTML, Javascript commands and internal comments (<span style="color:#FF0000;">limit access to users you trust!</span>)';
$module_home        = 'https://github.com/mrbaseman/code2';
$module_guid        = '968243F3-EC4A-439D-8936-7D727D9EF8C2';
