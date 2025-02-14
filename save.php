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

require('../../config.php');



/* -------------------------------------------------------- */
// Must include code to stop this file being accessed directly
if(!defined('WB_PATH')) {
        // Stop this file being access directly
        if(!headers_sent()) header("Location: ../index.php",TRUE,301);
        die('<head><title>Access denied</title></head><body><h2 style="color:red;margin:3em auto;text-align:center;">Cannot access this file directly</h2></body></html>');
}
/* -------------------------------------------------------- */



/**
 *    Include WB admin wrapper script
 *
 */
$update_when_modified = true; // Tells script to update when this page was last updated
$admin_header = false; // suppress to print the header, so no new FTAN will be set
require(WB_PATH.'/modules/admin.php');

if ( $admin->get_permission('start') == false ) die( header('Location: ../../index.php') );

/**
 *
 */
$hash_id = $_POST['code2_hash'];
if ($_SESSION['code2_hash'][$_POST['section_id']] != $hash_id) {
    die( header('Location: ../../index.php') );
} else {
    unset( $_SESSION['code2_hash'] );
}

/**
 *    Additional FTAN check ...
 *
 *
 */
$tan_ok = false;

if (true === method_exists($admin, 'checkFTAN')) {
    if ((WB_VERSION >= "2.8.2") && (!$admin->checkFTAN())) {
        $admin->print_header();
        $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'], WB_URL);
        $admin->print_footer();
        exit();
    } else {
        // After check print the header
        $admin->print_header();
        $tan_ok = true;
    }
} else {
    // After check print the header
    $admin->print_header();
    if (!isset($_SESSION['old_tan'][ $section_id ])) {
        // faild
    } else {

        $hash = $_SESSION['old_tan'][ $section_id ];

        unset( $_SESSION['old_tan']);

        $offset = hexdec($hash[0]);
        $str = substr($hash, $offset, 16);
        $name = substr($hash, 0, ( $offset * -1));

        if (!isset($_POST[ $name ])) {
            // faild
        } else {
            if ($_POST[ $name ] != $str) {
                // failed
            } else {
                // ok! seems to be ok
                $tan_ok = true;
            }
        }

        unset($offset);
        unset($str);
        unset($name);
    }
}
/**
 *    Keep in mind, that below 2.8.2 there is no $MESSAGE['GENERIC_SECURITY_ACCESS'] key!
 *
 */
if( false === $tan_ok) die( header('Location: ../../index.php') );

/**
 *    Update the mod_wysiwygs table with the contents
 *
 */
if ( isset($_POST['content']) ) {
    $tags       = array('<?php', '?>' , '<?');
    $content    = $admin->add_slashes(str_replace($tags, '', $_POST['content']));
    $whatis     = intval($_POST['whatis']);
    $highlight  = isset($_POST['highlighting']) ? 1 : 0;
    if (($whatis < 0) || ($whatis > 7)) $whatis = 0;
    if (($whatis === 4) && (!in_array(1, $admin->get_groups_id() ))) $whatis=3;
    $mode       = intval($_POST['mode']);
    if (($mode < 0) || ($mode > 2)) $mode = 0;
    $whatis     = $whatis + 10 * $mode;
    if(!$highlight) $whatis += 5;

    $fields = array(
        'content'    => $content,
        'whatis'    => $whatis
    );

    $query = "UPDATE `".TABLE_PREFIX."mod_code2` SET ";
    foreach($fields as $key=>$value) $query .= "`".$key."`=  '".$value."', ";
    $query = substr($query, 0, -2)." where `section_id`='".$section_id."'";

    $code2location= WB_PATH."/temp/modules/code2/section_".$section_id.".php.inc";

    if (file_exists($code2location)){
            if (!unlink ($code2location)) {
                    $admin->print_error("Cannot delete accesfile: $code2location", $js_back);
            }
    }

    $database->query($query);

    /**
     *    Check if there is a database error, otherwise say successful
     *
     */
    if ( true === $database->is_error() ) {
        $admin->print_error($database->get_error(), $js_back, true );
    } else {
        $admin->print_success($MESSAGE['PAGES_SAVED'],
            ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
    }
}

/**
 *    Print admin footer
 *
 */
$admin->print_footer();
