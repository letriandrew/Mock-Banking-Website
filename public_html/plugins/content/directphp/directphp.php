<?php
/**
 * @package     DirectPHP.Plugin
 * @subpackage  Content.directphp
 *
 * @copyright   Copyright (C) 2019 Clifford E Ford. All rights reserved.
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die;

use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Language\Text;

// See https://docs.joomla.org/J4.x:Creating_a_Plugin_for_Joomla

/**
 * Create and or render an article table of contents.
 *
 * @since  4.0
 */
class PlgContentDirectPHP extends CMSPlugin
{
	/**
	 * Look for {ToC} or {toc} and replace with Contents Panel.
	 *
	 * @return  void
	 *
	 * @since   4.0
	 */
	public function onContentPrepare($context, &$article, &$params, $page = 0)
	{
		// featured article with readmore separator needs {ToC} removed

		//echo "bp301.8 PlgContentDirectPHP<br>";

		$using_no_editor = $this->params->get('using_no_editor', "0");
		$block_list = $this->params->get('block_list', "basename, chgrp, chmod, chown, clearstatcache, copy, delete, dirname, disk_free_space, disk_total_space, diskfreespace, fclose, feof, fflush, fgetc, fgetcsv, fgets, fgetss, file_exists, file_get_contents, file_put_contents, file, fileatime, filectime, filegroup, fileinode, filemtime, fileowner, fileperms, filesize, filetype, flock, fnmatch, fopen, fpassthru, fputcsv, fputs, fread, fscanf, fseek, fstat, ftell, ftruncate, fwrite, glob, lchgrp, lchown, link, linkinfo, lstat, move_uploaded_file, opendir, parse_ini_file, pathinfo, pclose, popen, readfile, readdir, readllink, realpath, rename, rewind, rmdir, set_file_buffer, stat, symlink, tempnam, tmpfile, touch, umask, unlink, fsockopen, system, exec, passthru, escapeshellcmd, pcntl_exec, proc_open, proc_close, mkdir, rmdir");

		//echo "using_no_editor = " . $using_no_editor . "<br>";
		//echo "block_list = " . $block_list . "<br>";

		$block_list = preg_replace('/\s*/s', '', $block_list);
		$block_list = explode(',', $block_list);
		$this->block_list = $block_list;
		$this->errmsg = "";

		$php_start = "<?php";
		$php_end = "?>";
		$contents = $article->text;
		$contents = $this->fix_str($contents);
		$output = "";
		$regexp = '/(.*?)'.$this->fix_reg($php_start).'\s+(.*?)'.$this->fix_reg($php_end).'(.*)/s';
		$found = preg_match($regexp, $contents, $matches);
		while ($found) {
			$output .= $matches[1];
			$phpcode = $matches[2];
			#$phpcode2 = $this->fix_str2($phpcode);
			global $errmsg;
			if ($this->check_php($phpcode)) {
				ob_start();
				if ($using_no_editor) {
					eval($phpcode);
				} else {
					eval($this->fix_str2($phpcode));
				}
				$output .= ob_get_contents();
				ob_end_clean();
			} else {
				$output .= "The following command is not allowed: <b>$errmsg</b>";
			}
			$contents = $matches[3];
			$found = preg_match($regexp, $contents, $matches);
		}
		$output .= $contents;
		$article->text = $output;
		return;
	}


	function fix_str($str) {
		$str = str_replace('{?php', '<?php', $str);
		$str = str_replace('?}', '?>', $str);
		$str = preg_replace(array('%&lt;\?php(\s|&nbsp;|<br\s/>|<br>|<p>|</p>)%s', '/\?&gt;/s', '/-&gt;/'), array('<?php ', '?>', '->'), $str);
		return $str;
	}

	function fix_str2($str) {
		$str = str_replace('<br>', "\n", $str);
		$str = str_replace('<br />', "\n", $str);
		$str = str_replace('<p>', "\n", $str);
		$str = str_replace('</p>', "\n", $str);
		$str = str_replace('&#39;', "'", $str);
		$str = str_replace('&quot;', '"', $str);
		$str = str_replace('&lt;', '<', $str);
		$str = str_replace('&gt;', '>', $str);
		$str = str_replace('&amp;', '&', $str);
		$str = str_replace('&nbsp;', ' ', $str);
		$str = str_replace('&#160;', "\t", $str);
		$str = str_replace(chr(hexdec('C2')).chr(hexdec('A0')), '', $str);
		$str = str_replace(html_entity_decode("&Acirc;&nbsp;"), '', $str);
		return $str;
	}

	function fix_reg($str) {
		$str = str_replace('?', '\?', $str);
		$str = str_replace('{', '\{', $str);
		$str = str_replace('}', '\}', $str);
		return $str;
	}

	function check_php($code) {
		$status = 1;
		$function_list = array();
		if (preg_match_all('/([a-zA-Z0-9_]+)\s*[(|"|\']/s', $code, $matches)) {
			$function_list = $matches[1];
		}

		if (preg_match('/`(.*?)`/s', $code)) {
			$status = 0;
			$this->errmsg = 'backticks (``)';
			return $status;
		}
		if (preg_match('/\$database\s*->\s*([a-zA-Z0-9_]+)\s*[(|"|\']/s', $code, $matches)) {
			$status = 0;
			$this->errmsg = 'database->'.$matches[1];
			return $status;
		}
		foreach($function_list as $command) {
			if (in_array($command, $this->block_list)) {
				$status = 0;
				$this->errmsg = $command;
				break;
			}
		}
		return $status;
	}


	public function sample1($context, &$article, &$params, $page = 0)
	{

		if ($context === 'com_content.featured')
		{
			$article->text = str_ireplace('{ToC}', '', $article->text);
			return;
		}

		// the context could be something other than com_content
		// such as a module - in which case do nothing and return
		if ($context !== 'com_content.article')
		{
			return;
		}

		// return if there is no {ToC} in the article content
		if (stripos($article->text,'{ToC}') === false)
		{
			return;
		}

		// Load plugin language file only when needed
		$this->loadLanguage();

		// code to parse the text to extract headings to make into an index list

		// if a Read More separator is not set
		// $article->introtext contains the full text
		// $article->fulltext is empty.
		// if a Read More separator is set = <hr id="system-readmore" />
		// $article->introtext contains content from before the hr
		// $article->fulltext contains content from after the hr.
		// var_dump($context, $article->introtext);

		// get the headings and any text before the heading
		preg_match_all( '`(.*?)(<h[^>]+>)(.*?)(</h[^>]+>)`si', $article->text, $headings );
		// var_dump($headings);
		// $headings[1] contains the text before the next h tag
		// $headings[2] contains the opening h tag
		// $headings[3] contains the h tag content
		// $headings[4] contains the closing h tag

		// get the text after the last heading
		preg_match( '`.*</h[^>]+>(.*)`si', $article->text, $bottom );
		// var_dump($bottom);
		// $bottom[1] contains the text after the last closing h tag

		// $params contains the article parameters
		// $this->params contains the plugin parameters

		// get the Bootstrap card class parameter
		$toc_class = $this->params->get('toc_class', 'light');

		// make a list of the classes needed to render the Bootstrap cards
		$card_classes = array (
			'light' => 'bg-light',
			'info' => 'text-white bg-info',
			'primary' => 'text-white bg-primary',
			'secondary' => 'text-white bg-secondary',
			'success' => 'text-white bg-success',
			'danger' => 'text-white bg-danger',
			'warning' => 'text-white bg-warning'
		);

		// card links are not styled by bootstrap
		// so they have low contrast unless set here
		$card_link_classes = array (
				'light' => '',
				'info' => 'text-white',
				'primary' => 'text-white',
				'secondary' => 'text-white',
				'success' => 'text-white',
				'danger' => 'text-white',
				'warning' => 'text-white'
		);

		// get the card heading class
		$toc_head_class = $this->params->get('toc_head_class', 'center');
		if ($toc_head_class === 'left')
		{
			$headclass = 'text-left';
		}
		else
		{
			$headclass = 'text-center';
		}

		// get the list indent value (1, 2, or 3)
		$list_indent = $this->params->get('list_indent', 1);

		// assemble the html to generate the top part of the card
		$toc = '<div class="card ' . $card_classes[$toc_class] . '">';
		$toc .= '<div class="card-header" style="background-color: rgba(0,0,0,0.1);">';
		$toc .= '<h2 class="' . $headclass . '">' . Text::_('PLG_CONTENT_J4XDEMOSTOC_CONTENTS') . '</h2>';
		$toc .= '</div><div class="card-body">';
		$toc .= '<ul style="margin-left: 0;">';

		// the text of the article needs to have anchors added
		// so the revised article text is stored separately
		$text_with_anchors = '';

		// cycle through the headings to creat the ToC
		foreach (range(0, count($headings[0]) -1) as $i)
		{
			// get the heading level
			$hlevel = (int) substr($headings[2][$i],2,1);
			$addlink = $headings[2][$i] . '<a href="#j4xdemo' . $i . '" class="' . $card_link_classes[$toc_class] . '">' . $headings[3][$i] . '</a>' . $headings[4][$i];
			$toc .= '<li style="margin-left: ' . ($hlevel - 1) * $list_indent . 'rem; list-style-type: none; ">' . $addlink . '</li>' . "\n";

			// put an id into the heading <h2 id="anchorN">
			$anchor = str_replace('>',' id="j4xdemo' . $i . '">', $headings[2][$i]);

			// and here embed the anchor in the article
			$text_with_anchors .= $headings[1][$i] . $anchor . $headings[3][$i] . $headings[4][$i] . "\n";
		}
		// and don't forget whatever came after the last heading
		$text_with_anchors .= $bottom[1];
		$toc .= '</ul></div></div>';

		// replace the original article text with the new article text
		// and substitute the placeholde {Toc} with the generated ToC
		$article->text = str_ireplace("{toc}", $toc, $text_with_anchors);

		return;
	}
}