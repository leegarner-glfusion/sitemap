<?php

// this file can't be used on its own
if (!defined ('GVERSION')) {
    die ('This file can not be used on its own.');
}

class sitemap_staticpages extends sitemap_base
{

    public function getDisplayName()
    {
        global $LANG_STATIC;
        return $LANG_STATIC['staticpages'];
    }

    public function getChildCategories($pid = false, $all_langs = false)
    {
        return array();
    }

    /**
    * @param $all_langs boolean: true = all languages, true = current language
    * Returns array of (
    *   'id'        => $id (string),
    *   'title'     => $title (string),
    *   'uri'       => $uri (string),
    *   'date'      => $date (int: Unix timestamp),
    *   'image_uri' => $image_uri (string),
    *   'raw_data'  => raw data of the item (stripslashed)
    * )
    */
    public function getItemById($id, $all_langs = false)
    {
        global $_CONF, $_TABLES;

        $retval = array();

        $sql = "SELECT * "
             . "FROM {$_TABLES['stories']} "
             . "WHERE (sid ='" . DB_escapeString($id) . "') "
             . "AND (draft_flag = 0) AND (date <= NOW()) ";
        if ($this->uid > 0) {
            $sql .= COM_getTopicSql('AND', $this->uid);
            $sql .= COM_getPermSql('AND', $this->uid);
            if (function_exists('COM_getLangSQL') AND ($all_langs === false)) {
                $sql .= COM_getLangSQL('sid', 'AND');
            }
        }
        $result = DB_query($sql);
        if (DB_error()) {
            return $retval;
        }

        if (DB_numRows($result) == 1) {
            $A = DB_fetchArray($result, false);

            $retval['id']        = $id;
            $retval['title']     = $A['title'];
            $retval['uri']       = COM_buildUrl(
                $_CONF['site_url'] . '/article.php?story=' . $id
            );
            $retval['date']      = strtotime($A['date']);
            $retval['image_uri'] = false;
            $retval['raw_data']  = $A;
        }

        return $retval;
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
    public function getItems($tid = false, $all_langs = false)
    {
        global $_CONF, $_SP_CONF, $_TABLES;

        $retval = array();

        $sql = "SELECT sp_id, sp_title, UNIX_TIMESTAMP(sp_date) AS day
                FROM {$_TABLES['staticpage']}
                WHERE sp_search = 1 AND sp_status = 1 AND sp_date <= NOW()";
        if ($this->uid > 0) {
            $sql .= COM_getPermSql('AND', $this->uid);
            /*if (function_exists('COM_getLangSQL') AND ($all_langs === false)) {
                $sql .= COM_getLangSQL('sid', 'AND');
            }*/
        }
        if (in_array($_SP_CONF['sort_by'], array('id', 'title', 'date'))) {
            $crit = $_SP_CONF['sort_by'];
        } else {
            $crit = 'id';
        }
        $sql .= " ORDER BY sp_" . $crit;

        $result = DB_query($sql);
        if (DB_error()) {
            COM_errorLog("sitemap_staticpages::getItems error: $sql");
            return $retval;
        }

        while ($A = DB_fetchArray($result, false)) {
            $retval[] = array(
                'id'        => $A['sp_id'],
                'title'     => $A['sp_title'],
                'uri'       => COM_buildUrl(
                    $_CONF['site_url'] . '/page.php?page=' . $A['sp_id']
                ),
                'date'      => $A['day'],
                'imageurl'  => false,
            );
        }
        return $retval;
    }

}
?>
