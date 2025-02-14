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



function code2_search($func_vars) {
    extract($func_vars, EXTR_PREFIX_ALL, 'func');

    // how many lines of excerpt we want to have at most
    $max_excerpt_num = $func_default_max_excerpt;
    $divider = ".";
    $result = false;

    $table = TABLE_PREFIX."mod_code2";
    $query
       = $func_database->query(
           "SELECT `content`"
               . " FROM `$table`"
               . " WHERE `section_id`=$func_section_id"
               . " AND `whatis`=1"
         );

    if($query->numRows() > 0) {
        if($res = $query->fetchRow()) {
            $mod_vars = array(
                'page_link' => $func_page_link,
                'page_link_target' => "#wb_section_$func_section_id",
                'page_title' => $func_page_title,
                'page_description' => $func_page_description,
                'page_modified_when' => $func_page_modified_when,
                'page_modified_by' => $func_page_modified_by,
                'text' => $res['content'].$divider,
                'max_excerpt_num' => $max_excerpt_num
            );
            if(print_excerpt2($mod_vars, $func_vars)) {
                $result = true;
            }
        }
    }
    return $result;
}
