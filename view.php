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


include_once(WB_PATH.'/framework/functions.php');
include(WB_PATH.'/modules/code2/dirmaker.php');

/**
 *        Get content
 *
 */
$query="SELECT `content`, `whatis`"
        . " FROM `".TABLE_PREFIX."mod_code2`"
        . " WHERE `section_id`= '".$section_id."'";
$get_content = $database->query($query);

if (($get_content) && ($get_content->numRows() > 0)) {
    $fetch_content = $get_content->fetchRow();

    $whatis = (int)($fetch_content['whatis'] % 5);

    $content = $fetch_content['content'];

    switch ($whatis) {

        case 0:        // PHP
                $codelocation= WB_PATH."/temp/modules/code2/section_".$section_id.".php.inc";

                if (file_exists($codelocation) AND is_readable($codelocation)) {
                        // Include content
                        include ($codelocation);
                }
                else {
                        $wrapped_content = "<?php \nif (!defined('WB_PATH')) die(header('HTTP/1.0 404 Not Found').'404 Not Found');\n\n"
                                . $content."";

                        if( (false !== @file_put_contents($codelocation,$wrapped_content))) {
                                // Chmod the file
                                change_mode($codelocation);
                                include ($codelocation);
                        }
                        else {
                                echo "Cannot access datafile: $codelocation <br />";
                        }

                }
                break;

        case 1:        // HTML
               if( version_compare( WB_VERSION, '2.10.0', '<' )
                        && method_exists($wb,'preprocess')){
                        $wb->preprocess($content);
                }
                echo $content;
                break;

        case 2:        // JS
                echo "\n<script type=\"text/javascript\">\n<!--\n".$content."\n// -->\n</script>\n";
                break;

        case 3:
        case 4:
                echo "";        //Keine Ausgabe: Kommentar
                break;
        default:
                echo "Unknown type!";
                break;
    }
}

