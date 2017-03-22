<?php
/**
*   Common AJAX functions
*
*   @author     Lee Garner <lee@leegarner.com>
*   @copyright  Copyright (c) 2017 Lee Garner <lee@leegarner.com>
*   @package    sitemap
*   @version    2.0.0
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

switch ($_POST['action']) {
case 'toggleEnabled':
    $oldval = $_REQUEST['oldval'] == 1 ? 1 : 0;

    switch ($_POST['type']) {
    case 'html':
    case 'xml':
        $newval = SMAP_toggleEnabled($_POST['id'], $_POST['type'], $oldval);
        $newval_txt = $newval == 1 ? $LANG_SMAP['enabled'] : $LANG_SMAP['disabled'];
        break;

    default:
        exit;
    }
    $result = array(
        'newval' => $newval,
        'id' => $_POST['id'],
        'type' => $_POST['type'],
        'statusMessage' => sprintf($LANG_SMAP['smap_updated'],
                strtoupper($_POST['type']), ucwords($_POST['id']), $newval_txt),
    );
    break;

case 'updatefreq':
    USES_sitemap_class_config();
    $M = new smapConfig($_POST['id']);
    $newfreq = $M->updateFreq($_POST['newfreq']);
    $result = array(
        'pi_name'   => $_POST['id'],
        'newfreq'   => $newfreq,
        'statusMessage' => sprintf($LANG_SMAP['freq_updated'],
                ucwords($_POST['id']), $LANG_SMAP['freqs'][$newfreq]),
    );
    break;

case 'updatepriority':
    USES_sitemap_class_config();
    $M = new smapConfig($_POST['id']);
    $newpriority = $M->updatePriority($_POST['newpriority']);
    $result = array(
        'pi_name'   => $_POST['id'],
        'newpriority'   => $newpriority,
        'statusMessage' => sprintf($LANG_SMAP['prio_updated'],
                ucwords($_POST['id']), $newpriority),
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