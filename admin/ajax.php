<?php
/**
*   Common AJAX functions
*
*   @author     Lee Garner <lee@leegarner.com>
*   @copyright  Copyright (c) 2017 Lee Garner <lee@leegarner.com>
*   @package    sitemap
*   @version    0.1.7
*   @license    http://opensource.org/licenses/gpl-2.0.php 
*               GNU Public License v2 or later
*   @filesource
*/

/**
*  Include required glFusion common functions
*/
require_once '../../../lib-common.php';

if (!in_array('sitemap', $_PLUGINS) ||
    !SEC_hasRights('sitemap.admin')) {
    COM_404();
    exit;
}

switch ($_GET['action']) {
case 'toggleEnabled':
    $oldval = $_REQUEST['oldval'] == 1 ? 1 : 0;

    switch ($_GET['type']) {
    case 'smap':
    case 'gsmap':
        $newval = SMAP_toggleEnabled($_GET['id'], $_GET['type'], $oldval);
        break;

    default:
        exit;
    }
    $result = array(
        'newval' => $newval,
        'id' => $_GET['id'],
        'type' => $_GET['type'],
    );
    break;

case 'updatefreq':
    USES_sitemap_class_config();
    $M = new smapConfig($_GET['id']);
    $newfreq = $M->updateFreq($_GET['newfreq']);
    $result = array(
        'pi_name'   => $_GET['id'],
        'newfreq'   => $newfreq,
    );
    break;

case 'updatepriority':
    USES_sitemap_class_config();
    $M = new smapConfig($_GET['id']);
    $newpriority = $M->updatePriority($_GET['newpriority']);
    $result = array(
        'pi_name'   => $_GET['id'],
        'newpriority'   => $newpriority,
    );
    break;
}

$result = json_encode($result);
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-cache, must-revalidate');
//A date in the past
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');

echo $result;

?>
