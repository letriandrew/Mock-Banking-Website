<?php
/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/
//no direct access
defined('_JEXEC') or die ('Restricted access');

$report = array();
$report['items'] = $this->items;
$report['filters'] = $this->filters;

if($this->total > ($this->limit + $this->start)) {
	$report['pageNav'] 	= 'true';
} else {
	$report['pageNav'] 	= 'false';
}

echo json_encode($report); die;
