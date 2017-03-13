# SiteMap Plugin for glFusion
Version: 1.2.0

For the latest documentation, please see

	http://www.glfusion.org/wiki/doku.php?id=sitemap:start

## License
This program is free software; you can redistribute it and/or modify it under
the terms of the GNU General Public License as published by the Free Software
Foundation; either version 2 of the License, or (at your option) any later
version.

## Description
The SiteMap plugin creates a Google compatible sitemap.xml file of your
site's content. It also provides an interactive Site Map for your site's
users. A mobile-compatible sitemap named "mobile.xml" can also be created.

SiteMap honors content permissions, only showing those items which the
user has permissions to view.

## System Requirements
SiteMap has the following system requirements:

    * PHP 5.3.3 and higher.
    * glFusion v1.6.0 or newer

## Installation
The SiteMap  Plugin uses the glFusion automated plugin installer.
Simply upload the distribtuion using the glFusion plugin installer located in
the Plugin Administration page.

## Upgrading
The upgrade process is identical to the installation process, simply upload
the distribution from the Plugin Administration page.

## Plugin Integration
The Sitemap plugin can add data from other plugins to the online and XML
sitemaps. Each plugin must supply a class file named "plugin_name.class.php"
in the "sitemap" subdirectory under the plugin's private directory.
For example: private/plugins/myplugin/sitemap/myplugin.class.php

The class must be named "sitemap_<plugin_name>" to be used and should extend
the sitemap_base class provided by this plugin.

Example:
```php
class sitemap_myplugin extends sitemap_base
{

    /**
    *   Get the entry point for the plugin. Typically this is
    *   the index.php under public_html/plugin_name.
    *
    *   @return string      URL to plugin
    */
    public function getEntryPoint()
    {
        return 'http://mysite.com/myplugin/index.php';
    }


    /**
    *   Get the name of this sitemap driver. This is normally just the
    *   plugin name.
    *
    *   @return string      Name of driver (plugin)
    */
    public function getName()
    {
        return 'myplugin';
    }


    /**
    *   Get the friendly name for this plugin. This could be taken from a
    *   languange file.
    *
    *   @return string      Plugin friendly name
    */
    public function getDisplayName()
    {
        return 'My Plugin Name';
    }


    /**
    *   Get all the items for this plugin under the given category ID.
    *
    *   @param  mixed   $cat_id     Category ID
    *   @return array       Array of items
    */
    public function getItems($cat_id = 0)
    {
        return array(
            array(
                'id'    => Item 1 ID,
                'title' => Item title,
                'uri'   => URL to item
                'date'  => Last update timestamp,
                'image_url => URL to item's image,
            ),
            array(
                'id'    => Item 2 ID,
                'title' => Item title,
                'uri'   => URL to item
                'date'  => Last update timestamp,
                'image_url => URL to item's image,
            ),
            // ... etc.
        );
    }


    /**
    *   Get all the categories under the given base category.
    *
    *   @param  mixed   $base   Base category
    *   @return array       Array of categories
    */
    public function getChildCategories($base = false)
    {
        return array(
            array(
                'id'    => Category 1 ID,
                'title' => Category title,
                'uri'   => URL to category display page,
                'date'  => Last updated date (False if not used),
                'image_uri' => URL to category image,
            ),
            array(
                'id'    => Category 1 ID,
                'title' => Category title,
                'uri'   => URL to category display page,
                'date'  => Last updated date (False if not used),
                'image_uri' => URL to category image,
            ),
            // ... etc.
        );
    }
}
```
