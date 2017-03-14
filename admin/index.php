<?php
// +--------------------------------------------------------------------------+
// | Site Map Plugin for glFusion                                             |
// +--------------------------------------------------------------------------+
// | index.php                                                                |
// |                                                                          |
// | Administrative Interface                                                 |
// +--------------------------------------------------------------------------+
// | Copyright (C) 2014-2015 by the following authors:                        |
// |                                                                          |
// | Mark R. Evans          mark AT glfusion DOT org                          |
// |                                                                          |
// | Based on the Data Proxy Plugin                                           |
// | Copyright (C) 2007-2008 by the following authors:                        |
// |                                                                          |
// | Authors: mystral-kk        - geeklog AT mystral-kk DOT net               |
// +--------------------------------------------------------------------------+
// |                                                                          |
// | This program is free software; you can redistribute it and/or            |
// | modify it under the terms of the GNU General Public License              |
// | as published by the Free Software Foundation; either version 2           |
// | of the License, or (at your option) any later version.                   |
// |                                                                          |
// | This program is distributed in the hope that it will be useful,          |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of           |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            |
// | GNU General Public License for more details.                             |
// |                                                                          |
// | You should have received a copy of the GNU General Public License        |
// | along with this program; if not, write to the Free Software Foundation,  |
// | Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.          |
// |                                                                          |
// +--------------------------------------------------------------------------+

require_once '../../../lib-common.php';
require_once '../../auth.inc.php';

if (!in_array('sitemap', $_PLUGINS)) {
    COM_404();
    exit;
}

// Only let admin users access this page
if (!SEC_hasRights('sitemap.admin')) {
    // Someone is trying to illegally access this page
    COM_errorLog("Someone has tried to access the sitemap Admin page without proper permissions.  User id: {$_USER['uid']}, Username: {$_USER['username']}, IP: {$_SERVER['REMOTE_ADDR']}", 1);
    COM_404();
}

//=====================================
//  Functions
//=====================================

/**
* Creates a checkbox as follows:
*   <input id="sitemap_{$var_name}" name="{$var_name}" [checked="checked">
*    <lable for="sitemap_{$var_name}">{lang_{$var_name}}</label>
*/
function SITEMAP_createCheckBox($var_name) {
    global $_SMAP_CONF, $LANG_SMAP;

    $id = 'sitemap_' . $var_name;
    $retval = '<input id="' . $id . '" name="' . $var_name
            . '" type="checkbox" value="' . $var_name . '"';
    if ($_SMAP_CONF[$var_name] === true) {
        $retval .= ' checked="checked"';
    }
    $retval .= '><label for="' . $id . '">'
            . SITEMAP_str($var_name) . '</label>' . LB;
    return $retval;
}


/**
*   Returns options list for a frequency selection
*
*   @param  string  $selected   Optional value to mark selected
*   @return string      Options to be placed between <select> tags
*/
function SITEMAP_getFreqOptions($selected='')
{
    global $LANG_SMAP;

    foreach ($LANG_SMAP['freqs'] as $key=>$text) {
        $sel = $key == $selected ? 'selected="selected"' : '';
        $retval .= '<option value="' . $key . '" ' . $sel . '>' .
                $text . '</option>' . LB;
    }
    return $retval;
}


/**
*   Returns options list for a priority selection
*
*   @param  string  $selected   Optional value to mark selected
*   @return string      Options to be placed between <select> tags
*/
function SITEMAP_getPriorityOptions($selected='')
{
    global $_SMAP_CONF;
    foreach ($_SMAP_CONF['priorities'] as $value) {
        $sel = $value == $selected ? 'selected="selected"' : '';
        $retval .= '<option value="' . $value. '" ' . $sel . '>' .
                $value . '</option>' . LB;
    }
    return $retval;
}


/**
* Changes the display order of a given driver
*/
function SITEMAP_changeOrder($driver, $op) {
    global $_SMAP_CONF, $dataproxy;

    $all_supported_drivers = $dataproxy->getAllSupportedDriverNames();

    if (($op == 'up' OR $op == 'down')
            AND in_array($driver, $all_supported_drivers)) {
        $me = (int) $_SMAP_CONF['order_' . $driver];
        if ($op == 'up') {
            $you = $me - 1;
            if ($you <= 0) {
                $you = 1;
            }
        } else {
            $you = $me + 1;
            if ($you > count($all_supported_drivers)) {
                $you = count($all_supported_drivers);
            }
        }

        if ($me != $you) {
            foreach ($all_supported_drivers as $supported_driver) {
                if ((int) $_SMAP_CONF['order_' . $supported_driver] == $you) {
                    $_SMAP_CONF['order_' . $supported_driver] = $me;
                    $_SMAP_CONF['order_' . $driver]           = $you;
                    SITEMAP_saveConfig();
                    break;
                }
            }
        }
    }
}

//=====================================
//  Main
//=====================================

define('THIS_SCRIPT', $_CONF['site_admin_url'] . '/plugins/sitemap/index.php');
USES_sitemap_class_config();

// Loads Sitemap plugin configuration first of all
smapConfig::loadConfigs();

$expected = array(
    'move', 'updatenow',
);
foreach($expected as $provided) {
    if (isset($_POST[$provided])) {
        $action = $provided;
        $actionval = $_POST[$provided];
        break;
    } elseif (isset($_GET[$provided])) {
        $action = $provided;
        $actionval = $_GET[$provided];
        break;
    }
}

/*// Changes display order
if (isset($_GET['op']) AND isset($_GET['driver'])) {
    $op     = COM_applyFilter($_GET['op']);
    $driver = COM_applyFilter($_GET['driver']);
    SITEMAP_changeOrder($driver, $op);
}
*/
// Saves vars
/*if (isset($_POST['submit']) AND ($_POST['submit'] == $LANG_SMAP['submit'])) {
    //$all_drivers = $dataproxy->getAllSupportedDriverNames();

    foreach ($_POST['drivers'] as $driver) {
        $_SMAP_CONF['sitemap_' . $driver] = @in_array($driver, $_POST['sitemap_drivers']);
        $_SMAP_CONF['gsmap_' . $driver]   = @in_array($driver, $_POST['gsmap_drivers']);

        // Frequency
        $freq = COM_applyFilter($_POST['freq_' . $driver]);
        if (in_array($freq, $freqs)) {
            $_SMAP_CONF['freq_' . $driver] = $freq;
        }

        // Priority
        $priority = trim($_POST['priority_' . $driver]);
        $priority = (float) preg_replace("/[^0-9.-]/", '', $priority);
        if ($priority < 0.0 OR $priority > 1.0) {
            $priority = 0.5;
        }
        $_SMAP_CONF['priority_' . $driver] = $priority;
    }

    $_SMAP_CONF['anon_access']         = isset($_POST['anon_access']);
    $_SMAP_CONF['date_format']         = $_POST['date_format'];
    $_SMAP_CONF['google_sitemap_name'] = $_POST['google_sitemap_name'];
    $timezone = preg_replace("/[^0-9.:+-]/", '', $_POST['time_zone']);
    $_SMAP_CONF['time_zone'] = $timezone;

    // Saves config data and re-create the sitemap if necessary
    SITEMAP_saveConfig();

    if (isset($_POST['update_now'])) {
        SITEMAP_createGoogleSitemap();
    }
}
*/

USES_lib_admin();

$header = '';

$menu_arr = array (
    array('url' => $_CONF['site_admin_url'],
          'text' => $LANG_ADMIN['admin_home'])
);

$header .= COM_startBlock($LANG_SMAP['admin'], '',
                          COM_getBlockTemplate('_admin_block', 'header'));
$header .= ADMIN_createMenu($menu_arr, $LANG_SMAP['admin'], $_CONF['site_url'] . '/sitemap/images/sitemap.png');
$header .= COM_endBlock(COM_getBlockTemplate('_admin_block', 'footer'));

// Displays
$display = COM_siteHeader();
$display .= $header;
$T = new Template($_CONF['path'] . 'plugins/sitemap/templates');
$T->set_file('admin', 'admin.thtml');
$T->set_var('this_script', $_CONF['site_admin_url'] . '/plugins/sitemap/index.php');
$T->set_var('icon_url', $_CONF['site_url'] . '/sitemap/images/sitemap.png');
$T->set_var('lang_admin', SITEMAP_str('admin'));
$T->set_var('lang_sitemap_items', SITEMAP_str('sitemap_items'));
$T->set_var('lang_order', SITEMAP_str('order'));
$T->set_var('lang_sitemap_setting', SITEMAP_str('sitemap_setting'));
$T->set_var('lang_sitemap_setting_misc', SITEMAP_str('sitemap_setting_misc'));
$T->set_var('lang_gsmap_setting', SITEMAP_str('gsmap_setting'));
$T->set_var('lang_date_format', SITEMAP_str('date_format'));
$T->set_var('lang_desc_date_format', SITEMAP_str('desc_date_format', true));
$T->set_var('lang_google_sitemap_name', SITEMAP_str('google_sitemap_name'));
$T->set_var('lang_file_creation', SITEMAP_str('file_creation'));
$T->set_var('lang_time_zone', SITEMAP_str('time_zone'));
$T->set_var('lang_desc_filename', SITEMAP_str('desc_filename', true));
$T->set_var('lang_desc_time_zone', SITEMAP_str('desc_time_zone', true));
$T->set_var('lang_gsmap_items', SITEMAP_str('gsmap_items'));
$T->set_var('lang_item_name', SITEMAP_str('item_name'));
$T->set_var('lang_freq', SITEMAP_str('freq'));
$T->set_var('lang_priority', SITEMAP_str('priority'));
$T->set_var('lang_desc_freq', SITEMAP_str('desc_freq', true));
$T->set_var('lang_desc_priority', SITEMAP_str('desc_priority', true));
$T->set_var('lang_update_now', SITEMAP_str('update_now'));
$T->set_var('lang_last_updated', SITEMAP_str('last_updated'));
$T->set_var('lang_submit', SITEMAP_str('submit'));

switch ($action) {
case 'move':
    USES_sitemap_class_config();
    smapConfig::Move($_GET['id'], $actionval);
    break;
case 'updatenow':
    SITEMAP_createGoogleSitemap();
    break;
}

// Get any plugins that aren't already in the sitemap table and add them
smapConfig::cleanConfigs();

function SMAP_adminField($fieldname, $fieldvalue, $A, $icon_arr)
{
    global $_CONF, $LANG_ACCESS;

    $pi_name = $A['pi_name'];
    $retval = '';
    switch($fieldname) {
    case 'action':
        $retval = COM_createLink(
                '<img src="' . $_CONF['layout_url'] .
                '/images/up.png" height="16" width="16" border="0" />',
                $_CONF['site_admin_url'] . '/plugins/sitemap/index.php?move=up&id=' . $A['pi_name']
            ) .
            COM_createLink(
                '<img src="' . $_CONF['layout_url'] .
                    '/images/down.png" height="16" width="16" border="0" />',
                $_CONF['site_admin_url'] . '/plugins/sitemap/index.php?move=down&id=' . $A['pi_name']
            ) . LB;
        break;

    case 'gsmap_enabled':
    case 'smap_enabled':
        list($fldid, $trash) = explode('_', $fieldname);
        $chk = $fieldvalue == 1 ? 'checked="checked"' : '';
        $retval = "<input id=\"{$fldid}_ena_{$pi_name}\" type=\"checkbox\" name=\"{$fieldname}[{$A['pi_name']}]\" value=\"1\" $chk onclick='SMAP_toggleEnabled(this, \"{$A['pi_name']}\", \"{$fldid}\");' />" . LB;
         break;

    case 'orderby':
        $retval = "<input id=\"orderby_{$pi_name}\" type=\"text\" size=\"4\" value=\"$fieldvalue\" />" . LB;
        break;

    case 'freq':
        $retval = "<select id=\"freqsel_{$pi_name}\" name=\"freq[{$A['pi_name']}]\"
            onchange='SMAP_updateFreq(\"{$pi_name}\", this.value);'>" . LB;
        $retval .= SITEMAP_getFreqOptions($fieldvalue);
        $retval .= '</select>' . LB;
        break;

    case 'priority':
        $retval = "<select id=\"priosel_{$pi_name}\" name=\"priority[{$A['pi_name']}]\"
            onchange='SMAP_updatePriority(\"{$pi_name}\", this.value);'>" . LB;
        $retval .= SITEMAP_getPriorityOptions($fieldvalue);
        $retval .= '</select>' . LB;
        break;

    default:
        $retval = $fieldvalue;
        break;
    }
    return $retval;
}

/**
*   Uses lib-admin to list the form results.
*
*   @param  string  $frm_id         ID of form
*   @param  string  $instance_id    Optional form instance ID
*   @return string          HTML for the list
*/
function SMAP_adminList()
{
    global $_CONF, $_TABLES, $LANG_ADMIN, $_SMAP_MAPS, $_SMAP_CONF, $LANG_SMAP;

    $header_arr = array(
        array(  'text'  => $LANG_SMAP['order'],
                'field' => 'action',
                'sort'  => false,
        ),
        array(  'text'  => $LANG_SMAP['item_name'],
                'field' => 'pi_name',
                'sort'  => true,
        ),
        array(  'text'  => $LANG_SMAP['gsmap_enabled'],
                'field' => 'gsmap_enabled',
                'sort'  => false,
                'align' => 'center',
        ),
        array(  'text'  => $LANG_SMAP['smap_enabled'],
                'field' => 'smap_enabled',
                'sort' => false,
                'align' => 'center',
        ),
        array(  'text'  => $LANG_SMAP['freq'],
                'field' => 'freq',
                'sort' => false,
        ),
        array(  'text'  => $LANG_SMAP['priority'],
                'field' => 'priority',
                'sort' => false,
        ),
    );
    $defsort_arr = array('field' => 'orderby', 'direction' => 'asc');
    $retval .= ADMIN_listArray('simpleList', SMAP_adminField, 
                $header_arr, $text_arr,
                $_SMAP_MAPS, $defsort_arr, $filter, $extra,
                $options_arr, NULL);

    $T = new Template($_CONF['path'] . '/plugins/sitemap/templates');
    $T->set_file('update', 'updatemap.thtml');
    $sitemaps = explode(';', $_SMAP_CONF['google_sitemap_name']);
    $last_updated = @filemtime($_CONF['path_html'] . trim($sitemaps[0]));
    $D = new Date($last_updated, $_CONF['timezone']);
    if ($last_updated === false) {
        $last_updated = $LANG_SMAP['unknown'];
    } else {
        $last_updated = $D->format($_CONF['date'], true);
    }
    $T->set_var('last_updated', SITEMAP_escape($last_updated));
    $T->parse('output', 'update');
    $retval .= $T->finish($T->get_var('output'));
    return $retval;
}

$display .= SMAP_adminList();
$display .= COM_siteFooter();
echo $display;
exit;

foreach ($configs as $config) {
    $driver_name = $config['pi_name'];
        //$driver_name = $dataproxy->drivers[$supported_driver]->getDriverName();
    $id   = 'sitemap_admin_' . $supported_driver;
    $link = '<a href="' . THIS_SCRIPT . '?driver=' . $supported_driver
          . '&amp;op=up">' . SITEMAP_str('up') . '</a>&nbsp;'
          . '<a href="' . THIS_SCRIPT . '?driver=' . $supported_driver
          . '&amp;op=down">' . SITEMAP_str('down') . '</a>';

    $drivers .= '<tr><th style="text-align: left;"><input id="' . $id
        . '" name="sitemap_drivers[]" ' . 'type="checkbox" value="'
        . SITEMAP_escape($supported_driver) . '"';
    if ($_SMAP_CONF['sitemap_' . $supported_driver] === true) {
        $drivers .= ' checked="checked"';
    }
    $drivers .= '><label for="' . $id . '">'
                        . $driver_name
             . '</label></th><td>' . $link . '</td></tr>' . LB;
}

$T->set_var('sitemap_drivers', $drivers);

// Sets config vars for Google sitemap
$gsmap_drivers = '';

foreach ($configs as $config) {
        $supported_driver = $config['pi_name'];
        /*if (!isset($_SMAP_CONF['priority_' . $supported_driver])) {
            $_SMAP_CONF['priority_' . $supported_driver] = '0.5';
        }*/
    $id = 'gsmap_admin_' . $supported_driver;
    $gsmap_drivers .= '<tr><th style="text-align: left;"><input id="' . $id
                   .  '" name="gsmap_drivers[]" type="checkbox" value="'
                   .  SITEMAP_escape($supported_driver) . '"';
    if ($_SMAP_CONF['gsmap_' . $supported_driver] === true) {
        $gsmap_drivers .= ' checked="checked"';
    }
    $gsmap_drivers .= '><label for="' . $id . '">'
                . $dataproxy->drivers[$supported_driver]->getDriverName() . '</label></th>';

    // Frequency
    $gsmap_drivers .= '<td>' . SITEMAP_getFreqOptions($supported_driver)
                   .  '</td>' . LB;

    // Priority
    $gsmap_drivers .= '<td><input name="priority_' . $supported_driver
                   .  '" type="text" value="' . $_SMAP_CONF['priority_'
                   .  $supported_driver] . '" style="text-align: right;"></td>'
                   .  LB
                   .  '</tr>' . LB;
}

$T->set_var('gsmap_drivers', $gsmap_drivers);

$sitemap_fields = SITEMAP_createCheckBox('anon_access')      . '<br>' . LB;
$T->set_var('sitemap_fields', $sitemap_fields);
$T->set_var('time_zone', $_SMAP_CONF['time_zone']);
$T->set_var('date_format', $_SMAP_CONF['date_format']);
$T->set_var('google_sitemap_name', $_SMAP_CONF['google_sitemap_name']);

// Shows the last updated time of the Google sitemap
$filename = $_SMAP_CONF['google_sitemap_name'];
if (($pos = strpos($filename, ';')) !== false) {
    $filename = substr($filename, 0, $pos);
}
clearstatcache();
$last_updated = @filemtime($_CONF['path_html'] . $filename);
if ($last_updated === false) {
    $last_updated = SITEMAP_str('unknown');
} else {
    $last_updated = date('Y-m-d H:i:s', $last_updated);
}
$T->set_var('last_updated', SITEMAP_escape($last_updated));

$T->parse('output', 'admin');
$display .= $T->finish($T->get_var('output'))
         .  COM_siteFooter();

echo $display;
?>
