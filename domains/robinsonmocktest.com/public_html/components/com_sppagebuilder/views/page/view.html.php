<?php

/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */
//no direct access
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\Log\Log;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Component\ComponentHelper;

class SppagebuilderViewPage extends HtmlView
{

	protected $item;
	protected $canEdit;

	function display($tpl = null)
	{
		$app  = Factory::getApplication();
		$user = Factory::getUser();
		$this->item = $this->get('Item');

		// If a page is unpublished/trashed and a user tries to preview it. 
		if (is_string($this->item))
		{
			throw new Exception($this->item, 404);
		}

		$this->item = ApplicationHelper::preparePageData($this->item);

		$this->canEdit = $user->authorise('core.edit', 'com_sppagebuilder') ||
			$user->authorise('core.edit', 'com_sppagebuilder.page.' . $this->item->id) ||
			($user->authorise('core.edit.own', 'com_sppagebuilder.page.' . $this->item->id) && $this->item->created_by == $user->id);
		$this->checked_out = ($this->item->checked_out == 0 || $this->item->checked_out == $user->id);

		if (count($errors = (array) $this->get('Errors')))
		{
			Log::add(implode('<br />', $errors), Log::WARNING, 'jerror');
			return false;
		}

		// Temporary disabled
		if ($this->item->access_view == false)
		{
			$app->enqueueMessage(Text::_('JERROR_ALERTNOAUTHOR'), 'error');
			$app->setHeader('status', 403, true);

			return;
		}

		$this->_prepareDocument($this->item->title);



		parent::display($tpl);
	}

	protected function _prepareDocument($title = '')
	{
		$config = Factory::getConfig();
		$app = Factory::getApplication();
		$doc = Factory::getDocument();
		$menus = $app->getMenu();
		$menu = $menus->getActive();
		$config_params = ComponentHelper::getParams('com_sppagebuilder');
		$disable_og = $config_params->get('disable_og', 0);
		$disable_tc = $config_params->get('disable_tc', 0);

		//Title
		if (isset($meta['title']) && $meta['title'])
		{
			$title = $meta['title'];
		}
		else
		{
			if ($menu)
			{
				if ($menu->getParams()->get('page_title', ''))
				{
					$title = $menu->getParams()->get('page_title');
				}
				else
				{
					$title = $menu->title;
				}
			}
		}

		//Include Site title
		$sitetitle = $title;
		if ($config->get('sitename_pagetitles') == 2)
		{
			$sitetitle = Text::sprintf('JPAGETITLE', $sitetitle, $app->get('sitename'));
		}
		elseif ($config->get('sitename_pagetitles') == 1)
		{
			$sitetitle = Text::sprintf('JPAGETITLE', $app->get('sitename'), $sitetitle);
		}
		$doc->setTitle($sitetitle);

		$og_title = $this->item->og_title;
		$language = ($this->item->language == '*') ? Factory::getLanguage()->getTag() : $this->item->language;

		$this->document->addCustomTag('<meta property="article:author" content="' . $this->item->author_name . '"/>');
		$this->document->addCustomTag('<meta property="article:published_time" content="' . $this->item->created_on . '"/>');
		$this->document->addCustomTag('<meta property="article:modified_time" content="' . $this->item->modified . '"/>');
		$this->document->addCustomTag('<meta property="og:locale" content="' . $language . '" />');

		// Page Meta
		if (isset($this->item->attribs))
		{
			$attribs = json_decode($this->item->attribs);
		}
		else
		{
			$attribs = new stdClass;
		}

		if (!$disable_og)
		{
			if ($og_title)
			{
				$this->document->addCustomTag('<meta property="og:title" content="' . $og_title . '" />');
			}
			else
			{
				$doc->addCustomTag('<meta property="og:title" content="' . $title . '" />');
			}

			$og_type = (isset($attribs->og_type) && $attribs->og_type) ? $attribs->og_type : 'website';

			$this->document->addCustomTag('<meta property="og:type" content="' . $og_type . '" />');
			$this->document->addCustomTag('<meta property="og:url" content="' . Uri::getInstance()->toString() . '" />');

			if ($fb_app_id = $config_params->get('fb_app_id', ''))
			{
				$this->document->addCustomTag('<meta property="fb:app_id" content="' . $fb_app_id . '" />');
			}

			if ($config->get('sitename', ''))
			{
				$this->document->addCustomTag('<meta property="og:site_name" content="' . htmlspecialchars($config->get('sitename', '')) . '" />');
			}
		}

		$og_image = "";

		if (!empty($this->item->og_image))
		{
			$og_image = preg_match("@^{@", $this->item->og_image) ? json_decode($this->item->og_image)->src : $this->item->og_image;
		}


		if (!$disable_og && $og_image)
		{
			$this->document->addCustomTag('<meta property="og:image" content="' . Uri::root() . $og_image . '" />');
			$this->document->addCustomTag('<meta property="og:image:width" content="1200" />');
			$this->document->addCustomTag('<meta property="og:image:height" content="630" />');
		}

		$og_description = $this->item->og_description;
		if (!$disable_og && $og_description)
		{
			$this->document->addCustomTag('<meta property="og:description" content="' . $og_description . '" />');
		}

		if (!$disable_tc)
		{
			// Twitter
			$this->document->addCustomTag('<meta name="twitter:card" content="summary" />');

			if ($config->get('sitename', ''))
			{
				$this->document->addCustomTag('<meta name="twitter:site" content="' . htmlspecialchars($config->get('sitename', '')) . '" />');
			}

			if ($og_description)
			{
				$this->document->addCustomTag('<meta name="twitter:description" content="' . $og_description . '" />');
			}

			if ($og_image)
			{
				$this->document->addCustomTag('<meta name="twitter:image:src" content="' . Uri::root() . $og_image . '" />');
			}
		}


		$meta_description = (isset($attribs->meta_description) && $attribs->meta_description) ? $attribs->meta_description : '';
		$meta_keywords 	  = (isset($attribs->meta_keywords) && $attribs->meta_keywords) ? $attribs->meta_keywords : '';
		$robots 	  	  = (isset($attribs->robots) && $attribs->robots) ? $attribs->robots : '';


		if ($menu)
		{
			if ($menu->getParams()->get('menu-meta_description'))
			{
				$meta_description = $menu->getParams()->get('menu-meta_description');
			}

			if ($menu->getParams()->get('menu-meta_keywords'))
			{
				$meta_keywords = $menu->getParams()->get('menu-meta_keywords');
			}

			if ($menu->getParams()->get('robots'))
			{
				$robots = $menu->getParams()->get('robots');
			}
		}

		if (!empty($meta_description))
		{
			$this->document->setDescription($meta_description);
		}

		if (!empty($meta_keywords))
		{
			$this->document->setMetadata('keywords', $meta_keywords);
		}

		if (!empty($robots))
		{
			$this->document->setMetadata('robots', $robots);
		}
	}
}
