<?php

/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            https://www.tassos.gr
 * @copyright       Copyright © 2023 Tassos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
 */

namespace NRFramework\Widgets;

defined('_JEXEC') or die;

class OpenStreetMap extends Map
{
	/**
	 * Loads media files
	 * 
	 * @return  void
	 */
	public function loadMedia()
	{
		parent::loadMedia();
		
		\JHtml::stylesheet('plg_system_nrframework/vendor/leaflet.css', ['relative' => true, 'version' => 'auto']);
		\JHtml::script('plg_system_nrframework/vendor/leaflet.js', ['relative' => true, 'version' => 'auto']);
		
		if ($this->options['load_stylesheet'])
		{
			\JHtml::stylesheet('plg_system_nrframework/widgets/openstreetmap.css', ['relative' => true, 'version' => 'auto']);
		}

		if ($this->options['view'] === 'satellite')
		{
			\JHtml::script('https://unpkg.com/esri-leaflet@3.0.10/dist/esri-leaflet.js');
			\JHtml::script('https://unpkg.com/esri-leaflet-vector@4.0.1/dist/esri-leaflet-vector.js');
		}

		\JHtml::script('plg_system_nrframework/widgets/openstreetmap.js', ['relative' => true, 'version' => 'auto']);
	}
}