# Render Plus Plugin for DokuWiki


> This repository is archived because the renderer has been integated into [ComboStrap](https://combostrap.com).

## Old Description
Dokuwiki Rendering plugins that:

   * places a TOC after the first H1 header
   * numbers the headers
   * wrap the table element with [Bootstrap class](http://getbootstrap.com/css/#tables-responsive) to make them responsive

This plugin is a complement to the [Bootie Template](https://www.dokuwiki.org/template:bootie).

See the [Dokuwiki page of this plugin](https://www.dokuwiki.org/plugin:rplus).

## Installation 


Install the plugin:
  * using the [Plugin Manager](https://www.dokuwiki.org/plugin:plugin) and the download URL above, which points to latest version of the plugin. 
  * or manually. See [Plugins](https://www.dokuwiki.org/plugin_installation_instructions) on how to install plugins manually.

Then go to the [Configuration Setting: renderer_xhtml](https://www.dokuwiki.org/config:renderer_xhtml) and choose "Renderer Plus".

## Configuration 
### TOC (Table of Content)

In your template in order to get only one TOC, you need to call the function tpl_content with $prependTOC = false

```php
<?php tpl_content($prependTOC = false) ?>
```

See the function definition: [tpl_content](http://xref.dokuwiki.org/reference/dokuwiki/nav.html?_functions/tpl_content.html)

## Release
### 2018-07-22
  * TOC are now managed in the admin section because TOC may be added to show content in a admin plugin (Warf ...) Example with the SQLLite plugin.
  * Improvement of the you-are-here (with an house icon)
### 2018-05-01
  * [Refactored section edit Bug]()https://github.com/splitbrain/dokuwiki/pull/2220)
