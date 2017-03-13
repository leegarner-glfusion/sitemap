<?php
/**
*   Base class for sitemaps.
*   Derived from the Dataproxy plugin. Each plugin that wishes to
*   contribute a sitemap should supply a class in its "classes"
*   directory named "<plugin_name>.class.php". The class name should be
*   "sitemap_<plugin_name>" and extend this sitemap_base class.
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
*   Class for sitemap items
*/
class sitemap_base
{

    public $uid;

    public function __construct()
    {
        $this->uid = (int)$_USER['uid'];
    }

    /**
    *   Get the name of this sitemap class
    */
    public function getName()
    {
        return 'Unknown';
    }


    /**
    *   Get the display name of this sitemap class
    */
    public function getDisplayName()
    {
        return 'Unknown';
    }

    public function getEntryPoint()
    {
        global $_CONF;
        return $_CONF['site_url'];
    }


    public function getChildCategories($base_cat=false)
    {
        return array();
    }

    
    /**
    * Escapes a string for display
    *
    * @param  $str string: a string to escape
    * @return      string: an escaped string
    */
    public function Escape($str)
    {
        $str = str_replace(
            array('&lt;', '&gt;', '&amp;', '&quot;', '&#039;'),
            array(   '<',    '>',     '&',      '"',      "'"),
            $str
        );
        return htmlspecialchars($str, ENT_QUOTES);
    }
}

?>
