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


/**
 *    Load Language file
 */
$lang = (dirname(__FILE__))."/languages/". LANGUAGE .".php";
require_once ( !file_exists($lang) ? (dirname(__FILE__))."/languages/EN.php" : $lang );

// Setup template object
if(!class_exists('Template')){ require(WB_PATH.'/include/phplib/template.inc');}
$template = new Template(WB_PATH.'/modules/code2');
$template->set_file('page', 'htt/modify.htt');

// Get page content
$query = "SELECT `content`, `whatis`"
    . " FROM `".TABLE_PREFIX."mod_code2`"
    . " WHERE `section_id`= '".$section_id."'";
$get_content = $database->query($query);
$content = $get_content->fetchRow();
$whatis = (int)$content['whatis'];

$mode = ($whatis >= 10) ? (int)($whatis / 10) : 0;
$whatis = $whatis % 10;
$highlight = ($whatis < 5);
$whatis = $whatis % 5;

if(!$highlight){
   $template->set_block('page', 'script_block', 'script');
   $template->parse('script', 'dummy', false);
   $template->set_block('page', 'editor_block', 'editor');
   $template->parse('editor', 'dummy', false);
} else {
   $template->set_block('page', 'script_block', 'script');
   $template->parse('script', 'script_block', false);
   $template->set_block('page', 'editor_block', 'editor');
   $template->parse('editor', 'editor_block', false);
}

$template->set_block('page', 'main_block', 'main');

$groups = $admin->get_groups_id();

if ( ( $whatis == 4) AND (!in_array(1, $groups)) ) {
    $content = $content['content'];
    echo '<div class="code2_admin">'.$content.'</div>';
} else {

    /**
     *    Building the hash-id and store it inside the $_SESSION array.
     */
    $hash_id = md5( microtime()."just another day of hell" );

    $_SESSION['code2_hash'][$section_id] = $hash_id;

    /**
     *    FTAN Addition ...
     *
     *
     */
    if (true === method_exists($admin, 'getFTAN') ) {
        $tan = $admin->getFTAN();
    } else {

        $hash = md5( microtime() );
        $offset = hexdec( $hash[0] );
        $str = substr($hash, $offset, 16);
        $name = substr($hash, 0, ( $offset * -1) );
        $tan = "<input type='hidden' name='".$name."' value='".$str."' />";

        if (!isset($_SESSION['old_tan'])) $_SESSION['old_tan'] = array();
        $_SESSION['old_tan'][$section_id] = $hash;

        unset($hash);
        unset($offset);
        unset($str);
        unset($name);
    }

    $content = htmlspecialchars($content['content']);
    $whatis_types = array('PHP', 'HTML', 'Javascript', 'Internal');
    if (in_array(1, $groups)) $whatis_types[]="Admin";

    $whatisarray = array();
    foreach($whatis_types as $item) $whatisarray[] = $MOD_CODE2[strtoupper($item)];

    $whatisselect = '';
    for($i=0; $i < count($whatisarray); $i++) {
           $select = ($whatis == $i) ? " selected='selected'" : "";
           $entry = $whatisarray[$i];
           if($entry != ""){
               $whatisselect .= '<option value="'.$i.'"'.$select.'>'
              .$entry.'</option>'."\n";
           }
      }

    $modes_names = array('smart', 'full');
    if($highlight) $modes_names[] = 'auto';

    $modes = array();
    foreach($modes_names as $item) $modes[] = $MOD_CODE2[strtoupper($item)];
    $mode_options = "";
    $counter = 0;
    foreach($modes as $item) {
        $mode_options .= "<option value='".$counter."' "
            .(($counter==$mode)?" selected='selected'":"").">".$item."</option>";
        $counter++;
    }

    // Insert vars
    $template->set_var(array(
            'PAGE_ID'      => $page_id,
            'SECTION_ID'   => $section_id,
            'WB_URL'       => WB_URL,
            'CONTENT'      => $content,
            'WHATIS'       => $whatis,
            'HIGHLIGHTING' => $highlight ? "checked" : "",
            'WHATISSELECT' => $whatisselect,
            'TEXT_SAVE'    => $TEXT['SAVE'],
            'TEXT_CANCEL'  => $TEXT['CANCEL'],
            'MODE'         => $mode_options,
            'LANGUAGE'     => LANGUAGE,
            'MODES'        => $MOD_CODE2['MODE'],
            'CODE2_HASH'   => $hash_id,
            'FTAN'         => $tan
        )
    );

    // Parse template object
    $template->parse('main', 'main_block', false);
    $template->pparse('output', 'page', false, false);

    unset($tan);
}
