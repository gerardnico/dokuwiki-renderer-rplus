<?php
/**
 * Renderer for XHTML output
 *
 * @author Harry Fuecks <hfuecks@gmail.com>
 * @author Andreas Gohr <andi@splitbrain.org>
 */
if (!defined('DOKU_INC')) die('meh.');


require_once DOKU_INC . 'inc/parser/xhtml.php';

/**
 * DokuWiki Plugin nicorender (Renderer Component)
 *
 * The Nico XHTML Renderer
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author  Nicolas GERARD <gerardnico@gmail.com>
 *
 * This is a replacement render of the DokuWiki's main renderer
 * That format the content that's output the tpl_content function.
 */
class  renderer_plugin_rplus_renderer extends Doku_Renderer_xhtml
{


    /** @var array that hold the current position of an header for a level
     * $headerNum[level]=position
     * */
    protected $headerPos = [];

    /**
     * @var array that will contains the whole doc but by section
     */
    protected $sections = [];

    /**
     * @var the section number
     */
    protected $sectionNumber = 0;

    /**
     * @var variable that permits to carry the header text of a previous section
     */
    protected $previousSectionTextHeader = '';

    /**
     * @var variable that permits to carry the level of a previous section
     */
    protected $previousLevel = 0;

    /**
     * @var variable that permits to carry the position of a previous section
     */
    protected $previousPosition = 0;

    function getFormat()
    {
        return 'xhtml';
    }

    /*
     * Function that enable to list the plugin in the options for config:renderer_xhtml
     * http://www.dokuwiki.org/config:renderer_xhtml
     * setting in its Configuration Manager.
     */
    public function canRender($format)
    {
        return ($format == 'xhtml');
    }


    /**
     * Render a heading
     *
     * @param string $text the text to display
     * @param int $level header level
     * @param int $pos byte position in the original source
     */
    function header($text, $level, $pos)
    {
        global $conf;

        // Keeping the position of each header for its level
        if (array_key_exists($level, $this->headerPos)) {
            $position = $this->headerPos[$level] + 1;
        } else {
            $position = 1;
        }
        $this->headerPos[$level] = $position;

        // Pump the doc from the previous section
        $this->sections[$this->sectionNumber] = array('level' => $this->previousLevel, 'position' => $this->previousPosition, 'content' => $this->doc, 'text' => $this->previousSectionTextHeader);
        // And reset it
        $this->doc = '';
        // Set the looping variable
        $this->sectionNumber = $this->sectionNumber + 1;
        $this->previousLevel = $level;
        $this->previousPosition = $position;
        $this->previousSectionTextHeader = $text;

        $numbering = "";
        if ($level == 2) {
            $numbering = $position;
        }
        if ($level >= 3) {
            $numbering = $level.".".$position;
        }
        if ($numbering <> "") {
            $textWithLocalization = $numbering." - ". $text;
        } else {
            $textWithLocalization = $text;
        }
        parent::header($textWithLocalization, $level, $pos);


    }


    function document_end()
    {

        global $ID;

        // Pump the last doc
        $this->sections[$this->sectionNumber] = array('level' => $this->previousLevel, 'position' => $this->previousPosition, 'content' => $this->doc, 'text' => $this->previousSectionTextHeader);

        // Recreate the doc
        $this->doc = '';
        foreach ($this->sections as $sectionNumber => $section) {



            // The content
            $this->doc .= $section['content'];

            // No TOC or bar for an admin page
            global $ACT;
            if ($ACT <> 'admin' ) {


                // TOC After the content
                if ($this->info['toc'] == true and $section['level'] == 1 and $section['position'] == 1) {

                    // Needed to render the doc created during the
                    // renderer process
                    // Otherwise it uses the metadata cache ...
                    global $TOC;
                    $TOC = $this->toc;
                    $this->doc .= tpl_toc($return = true);


                }

                // Adbar after the content
                // Adbar later
//                if ($section['level'] == 2 and
//                    $section['position'] == 1 and
//                    $ID <> 'adbar12' and
//                    $ID <> 'start'
//                ) {
//
//                    // $ID <> 'adbar12' to not come in a recursive call
//                    // as tpl_include_call also the renderer process
//
//                    $this->doc .= tpl_include_page('adbar12', $print = false, $propagate = true);
//
//                }

            }



        }

        parent::document_end();

    }


}
