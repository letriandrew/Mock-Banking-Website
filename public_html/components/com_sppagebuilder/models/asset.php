<?php
/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */

// No direct access
defined ('_JEXEC') or die ('Restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Filesystem\Folder;

/**
 * Asset Model class
 * 
 * @since 4.0.0
 */
class SppagebuilderModelAsset extends ListModel
{
	private $columns = [];

    public function __construct($config = [])
	{
		parent::__construct($config);
		$this->columns = ['id', 'type', 'name', 'title', 'assets', 'css_path', 'published', 'access', 'created', 'created_by'];
	}

	/**
	 * Load the custom icons.
	 *
	 * @return	array	The icons array.
	 * @since	4.0.0
	 */
	public function loadCustomIcons() : array
	{
		$db 	= Factory::getDbo();
		$query 	= $db->getQuery(true);

		$query->select($db->quoteName($this->columns))
			->from($db->quoteName('#__sppagebuilder_assets'))
			->where($db->quoteName('published') . ' IN (0, 1)');
		
		$db->setQuery($query);

		try
		{
			$result = $db->loadObjectList();
		}
		catch (\Exception $e)
		{
			return [];
		}

		if (!empty($result))
		{
			foreach ($result as &$icon)
			{
				$path = 'components/com_sppagebuilder/assets/images/customIcons/' . $icon->name . '.jpg';

				if (\file_exists(JPATH_ROOT . '/' . $path))
				{
					$icon->thumb = Uri::root() . '/' . $path;
				}
				else
				{
					$icon->thumb = Uri::root() . '/components/com_sppagebuilder/assets/images/customIcons/default.jpg';
				}
			}

			unset($icon);
		}

		return $result;
	}

	/**
	 * Delete custom icon by ID.
	 *
	 * @param	int		$id		The icon id to remove.
	 *
	 * @return	bool	True on success, false otherwise.
	 * @since	4.0.0
	 */
	public function deleteCustomIcon(int $id) : bool
	{
		$asset = $this->getAssetById($id);
		$assetName = isset($asset->name) ? $asset->name : '';
		$assetPath = JPATH_ROOT . '/media/com_sppagebuilder/assets/iconfont/' . $assetName;

		$db 	= Factory::getDbo();
		$query 	= $db->getQuery(true);
		$query->delete($db->quoteName('#__sppagebuilder_assets'))
			->where($db->quoteName('id') . ' = ' . $id);
		$db->setQuery($query);

		if (Folder::exists($assetPath))
		{
			Folder::delete($assetPath);
		}

		try
		{
			return $db->execute() !== false;
		}
		catch (\Exception $e)
		{
			return false;
		}

		return false;
	}

	/**
	 * Change the status of the custom icon's status.
	 *
	 * @param	int		$id		The icon id.
	 * @param	int		$status	The updating status.
	 *
	 * @return	void
	 * @since	4.0.0
	 */
	public function changeCustomIconStatus(int $id, int $status) : bool
	{
		$db 	= Factory::getDbo();
		$query 	= $db->getQuery(true);

		$query->update($db->quoteName('#__sppagebuilder_assets'))
			->set($db->quoteName('published') . ' = ' . $status)
			->where($db->quoteName('id') . ' = ' . $id);

		$db->setQuery($query);

		try
		{
			return $db->execute() !== false;
		}
		catch (\Exception $e)
		{
			return false;
		}

		return false;
	}

    public function insertAsset($data)
	{
		$dataObject = (object) $data;
		$dataObject->id = null;

		$db = Factory::getDbo();

		try
		{
			if (($icon = $this->getAssetByName($dataObject->name)))
			{
				$dataObject->id = $icon->id;
				$result = $db->updateObject('#__sppagebuilder_assets', $dataObject, 'id', true);
			}
			else
			{
				$result = $db->insertObject('#__sppagebuilder_assets', $dataObject, 'id');
			}

			$result = $this->getAssetByName($dataObject->name);
			$path = 'components/com_sppagebuilder/assets/images/customIcons/' . $result->name . '.jpg';

			if (\file_exists(JPATH_ROOT . '/' . $path))
			{
				$result->thumb = Uri::root() . '/' . $path;
			}
			else
			{
				$result->thumb = Uri::root() . '/components/com_sppagebuilder/assets/images/customIcons/default.jpg';
			}

			return $result;
		}
		catch (Exception $e)
		{
			return false;
		}
	}

	public function getAssetProviders()
	{
		$db = Factory::getDbo();
		$query = $db->getQuery(true);
		$query->select('DISTINCT ' . $db->quoteName('name') . ', title');
		$query->from($db->quoteName('#__sppagebuilder_assets'));
		$query->where($db->quoteName('published') . ' = 1');
		
		$db->setQuery($query);

		$result = $db->loadObjectList();
		return $result;
	}

	public function getIconList($name)
	{
		$db = Factory::getDbo();
		$query = $db->getQuery(true);
		$query->select($db->quoteName('assets'));
		$query->from($db->quoteName('#__sppagebuilder_assets'));
		$query->where($db->quoteName('name').' = '. $db->quote($name));
		$db->setQuery($query);

		$result = $db->loadResult();
		return $result;
	}

	public function getAssetByName(string $name)
	{
		$db 	= Factory::getDbo();
		$query 	= $db->getQuery(true);
		$query->select('*')->from($db->quoteName('#__sppagebuilder_assets'))
			->where($db->quoteName('name') . ' = ' . $db->quote($name));
		$db->setQuery($query);

		return $db->loadObject();
	}

	public function getAssetById(int $id)
	{
		$db 	= Factory::getDbo();
		$query 	= $db->getQuery(true);
		$query->select('*')->from($db->quoteName('#__sppagebuilder_assets'))
			->where($db->quoteName('id') . ' = ' . $db->quote($id));
		$db->setQuery($query);

		return $db->loadObject();
	}
}
