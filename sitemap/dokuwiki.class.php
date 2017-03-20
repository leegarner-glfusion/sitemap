<?php
// +--------------------------------------------------------------------------+
// | Sitemap driver for the Dokuwiki plugin
// +--------------------------------------------------------------------------+
// | dokuwiki.class.php                                                       |
// |                                                                          |
// | DokuWiki Plugin interface                                                |
// +--------------------------------------------------------------------------+
// | Copyright (C) 2009-2017 by the following authors:                        |
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

// this file can't be used on its own
if (!defined ('GVERSION')) {
    die ('This file can not be used on its own.');
}

class sitemap_dokuwiki extends sitemap_base
{
    protected $name = 'dokuwiki';

    /**
    * Returns the location of index.php of each plugin
    */
    public function getEntryPoint()
    {
        global $_CONF, $_DW_CONF;
        return $_CONF['site_url'] . $_DW_CONF['public_dir']     .'doku.php';
    }


    /**
    *   Get the friendly display name
    *
    *   @return string  Display name
    */
    public function getDisplayName()
    {
        global $LANG_DW00;
        return $LANG_DW00['menulabel'];
    }


    public function getChildCategories($pid = false)
    {
        global $conf;
        global $QUERY;
        global $ID;
        global $LANG_DW00;
        global $_CONF;
        global $_DW_CONF;
        global $_USER;

        require_once (DOKU_INC.'inc/init.php');
        require_once (DOKU_INC.'inc/common.php');
        require_once (DOKU_INC.'inc/events.php');
        require_once (DOKU_INC.'inc/pageutils.php');
        require_once (DOKU_INC.'inc/html.php');
        require_once (DOKU_INC.'inc/auth.php');
        require_once (DOKU_INC.'inc/actions.php');
        require_once(DOKU_INC.'inc/indexer.php');
        require_once ($_CONF['path_html'] . $_DW_CONF['public_dir'] . 'inc/search.php');
        require_once ($_CONF['path_html'] . $_DW_CONF['public_dir'] . 'inc/fulltext.php');

        $dir = $conf['datadir'];
        $ns  = cleanID($pid);
        if(empty($ns)){
            $ns = dirname(str_replace(':','/',$ID));
            if($ns == '.') $ns ='';
        }
        $ns  = utf8_encodeFN(str_replace(':','/',$ns));
        $data = array();
        search($data,$conf['datadir'],'search_index',array('ns' => $ns));
        $entries = array();
        foreach ($data as $item) {
            if ( $item['type'] == 'd') {
                if ( !empty($ns) && strstr($item['id'],$ns) == false ) {
                    continue;
                }
                if ( !empty($ns) && $ns == $item['id'] ) {
                    continue;
                }
                $entry = array();
                $entry['id'] = $item['id'];
                $entry['pid'] = $pid;
                $entry['title'] = p_get_metadata($item['id'], 'title');
                if ( empty($entry['title']) ) $entry['title'] = $item['id'];
                switch ($conf['userewrite']) {
                    case 1: // URL rewrite - .htaccess
                        $entry['uri'] = $_CONF['site_url'].$_DW_CONF['public_dir'].$entry['id'];
                        break;
                    case 0: // URL rewrite - off
                        $entry['uri'] = $_CONF['site_url'] . $_DW_CONF['public_dir']
                                      . 'doku.php?id=' . $entry['id'];
                        break;
                    case 2: // URL rewrite - internal
                        $entry['uri'] = $_CONF['site_url'] . $_DW_CONF['public_dir']
                                      . 'doku.php/' . $entry['id'];
                        break;
                    default:
                        break;
                }
                $entry['date']      = false;
                $entry['image_uri'] = false;

                $entries[] = $entry;
            }
        }
        return $entries;
    }


    /**
    * Returns an array of (
    *   'id'        => $id (string),
    *   'title'     => $title (string),
    *   'uri'       => $uri (string),
    *   'date'      => $date (int: Unix timestamp),
    *   'image_uri' => $image_uri (string)
    * )
    */
    public function getItems($category = false)
    {
        global $conf;
        global $QUERY;
        global $ID;
        global $LANG_DW00;
        global $_CONF;
        global $_DW_CONF;
        global $_USER;

        require_once (DOKU_INC.'inc/init.php');
        require_once (DOKU_INC.'inc/common.php');
        require_once (DOKU_INC.'inc/events.php');
        require_once (DOKU_INC.'inc/pageutils.php');
        require_once (DOKU_INC.'inc/html.php');
        require_once (DOKU_INC.'inc/auth.php');
        require_once (DOKU_INC.'inc/actions.php');
        require_once(DOKU_INC.'inc/indexer.php');
        require_once ($_CONF['path_html'] . $_DW_CONF['public_dir'] . 'inc/search.php');
        require_once ($_CONF['path_html'] . $_DW_CONF['public_dir'] . 'inc/fulltext.php');

        $dir = $conf['datadir'];
        $ns = $category;
        #fixme use appropriate function
        if(empty($ns)){
            $ns = dirname(str_replace(':',DIRECTORY_SEPARATOR,$ID));
            if($ns == '.') $ns ='';
        }
        $ns  = utf8_encodeFN(str_replace(':',DIRECTORY_SEPARATOR,$ns));

        $data = array();
        search($data,$conf['datadir'],'search_index',array('ns' => $ns));

        $base_path = $_CONF['path_html'] . substr($_DW_CONF['public_dir'], 1);
        $data_path = realpath($base_path . $conf['savedir'] . '/pages');
        if ($data_path === false) {
            COM_errorLog("Dataproxy: can't find DokuWiki's data directory.");
            return $retval;
        }

        $entries = array();
        foreach($data AS $item) {
            if ( $item['type'] == 'f' ) {

                if ( !empty($category) && strstr($item['id'],$category) == false ) {
                    continue;
                }
                if ( !empty($category) && $category == $item['id'] ) {
                    continue;
                }
                $entry = array();
                $entry['id']    = $item['id'];
                $entry['title'] = p_get_metadata($item['id'], 'title');
                if ( empty($entry['title'])) {
                    if ( substr($entry['id'],0,strlen($category)) == $category) {
                        $entry['title'] = substr($entry['id'],strlen($category)+1);
                    } else {
                        $entry['title'] = urldecode($entry['id']);
                    }
                }
                switch ($conf['userewrite']) {
                    case 1: // URL rewrite - .htaccess
                        $entry['uri'] = $_CONF['site_url'].$_DW_CONF['public_dir'].$entry['id'];
                        break;

                    case 0: // URL rewrite - off
                        $entry['uri'] = $_CONF['site_url'] . $_DW_CONF['public_dir']
                                      . 'doku.php?id=' . $entry['id'];
                        break;

                    case 2: // URL rewrite - internal
                        $entry['uri'] = $_CONF['site_url'] . $_DW_CONF['public_dir']
                                      . 'doku.php/' . $entry['id'];
                        break;

                    default:
                        break;
                }
                if ( substr($entry['id'],0,strlen($category)) == $category) {
                    $fileid = $ns . DIRECTORY_SEPARATOR. substr($entry['id'],strlen($category)+1);
                } else {
                    $fileid = urldecode($entry['id']);
                }
                $full_path = $data_path . DIRECTORY_SEPARATOR . $fileid . '.txt';
                $entry['date']      = @filemtime($full_path);
                $entry['image_uri'] = false;

                $entries[] = $entry;
            }
        }
        return $entries;
    }
}
?>
