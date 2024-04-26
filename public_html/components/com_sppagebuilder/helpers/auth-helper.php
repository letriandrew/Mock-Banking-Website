<?php

/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */


/** No direct access. */
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\User\User;
use Joomla\CMS\User\UserHelper;

/**
 * The authentication helper class for authenticating user.
 *
 * @since 	4.0.0
 */
class AuthHelper
{

	/**
	 * Securely login user by the username.
	 *
	 * @param 	string 	$username	The username.
	 *
	 * @return	void
	 * @since 	4.0.0
	 */
	public static function loginUserByUsername(string $username)
	{
		$user = User::getInstance();
		$userId = UserHelper::getUserId($username);

		if (!empty($userId))
		{
			/** Load the user data by using the ID. */
			$user->load($userId);

			$isAuthorisedUser = $user->authorise('core.login.site');

			if ($isAuthorisedUser)
			{
				$user->guest = 0;
				$session = Factory::getSession();

				/** Preserve the old session ID. */
				$prevSessionId = $session->getId();

				/** Fork the session and create a new instance. */
				$session->fork();

				/** Update the user information to the session. */
				$session->set('user', $user);

				$app = Factory::getApplication();
				$app->checkSession();

				/** Delete the old session from the database. */
				$db = Factory::getDbo();
				$query = $db->getQuery(true);
				$query->delete('#__session')
					->where($db->quoteName('session_id') . ' = ' . $db->quote($prevSessionId));
				$db->setQuery($query);

				try
				{
					$db->execute();
				}
				catch (Exception $e)
				{
					$app->enqueueMessage('Error deleting session: ' . $e->getMessage());
				}

				/** Pass through method to the table for setting the last visit date. */
				$user->setLastVisit();

				/** Update the cookie. */
				$app->input->cookie->set(
					'joomla_user_state',
					'logged_in',
					0,
					$app->get('cookie_path', '/'),
					$app->get('cookie_domain', ''),
					$app->isHttpsForced(),
					true
				);
			}
		}
	}

	/**
	 * Generate the secure link for visiting from administrator to site.
	 *
	 * @return	string 	The generated link url.
	 * @since 	4.0.0
	 */
	public static function generateLink(string $path = '')
	{
		$user = Factory::getUser();
		$link = Uri::root() . 'index.php?option=com_sppagebuilder&view=dashboard&tmpl=component';

		$link .= '&username=' . urlencode($user->username);
		$link .= '&password=' . urlencode($user->password);

		$link .= '#/' . $path;

		return $link;
	}

	private static function checkCredibility($username, $password)
	{
		$db 	= Factory::getDbo();
		$query 	= $db->getQuery(true);

		$query->select('username')
			->from($db->quoteName('#__users'))
			->where($db->quoteName('username') . ' = ' . $db->quote($username))
			->where($db->quoteName('password') . ' = ' . $db->quote($password));
		$db->setQuery($query);

		try
		{
			return !empty($db->loadResult());
		}
		catch (Exception $e)
		{
			return false;
		}

		return false;
	}

	/**
	 * Check the user credibility before visiting the dashboard.
	 *
	 * @return 	void
	 * @since 	4.0.0
	 */
	public static function loginBeforePassThrough()
	{
		$app = Factory::getApplication();
		$input = $app->input;
		$user = Factory::getUser();

		$username = $input->get('username', '', 'raw');
		$password = $input->get('password', '', 'raw');

		if (!$user->authorise('core.admin', 'com_sppagebuilder') && self::checkCredibility($username, $password))
		{
			self::loginUserByUsername($username);
		}

		if (!empty($username) || !empty($password))
		{
			$GET = $input->get->getArray([]);
			unset($GET['username']);
			unset($GET['password']);

			$url = http_build_query($GET);
			header('Location: ' . Uri::current() . '?' . $url);
			exit;
		}

		return false;
	}
}
