<?php
/**
* @package     Sppagebuilder.Administrator
*
* @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
* @license     GNU General Public License version 2 or later; see LICENSE.txt
*/

defined('_JEXEC') or die;

include __DIR__ . '/../assets/vendor/autoload.php';

use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Http\Http;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\MVC\Controller\BaseController;

/**
* Installer controller for Joomla! installer class.
*
* @since  1.5
*/
class SppagebuilderControllerInstagram extends BaseController
{
	public function accessToken()
	{
		$app   = Factory::getApplication();
		$doc   = Factory::getDocument();
		$input = $app->input;
		$http  = new Http;

		/**
		 * Get APP ID and APP SECRET from the component param
		 */
		$params      = ComponentHelper::getParams('com_sppagebuilder');
		$igToken     = json_decode($params->get('ig_token', '{}'));
		$appId       = $igToken->appId;
		$appSecret   = $igToken->appSecret;
		$redirectUrl = Uri::root() . 'administrator/index.php?option=com_sppagebuilder&task=instagram.accessToken';

		/**
		 * Instantiate the Facebook API with the help of appID and appSecret
		 */
		$facebook = new Facebook\Facebook(
			[
				'app_id'                  => $appId,
				'app_secret'              => $appSecret,
				'default_graph_version'   => 'v7.0',
				'persistent_data_handler' => 'session',
			]
		);

		/**
		 * Get Facebook SDK redirect helper and OAuth2 Client
		 */
		$helper       = $facebook->getRedirectLoginHelper();
		$oAuth2Client = $facebook->getOAuth2Client();

		$token = '';
		$igId  = '';

		/**
		 * If code not provided to the url then redirect to the facebook
		 * login page.
		 */
		if ($input->get('code', '') === '')
		{
			$permissions = [
				'public_profile',
				'instagram_basic',
				'pages_show_list',
			];

			$loginUrl = $helper->getLoginUrl($redirectUrl, $permissions);
			$app->redirect($loginUrl);
		}
		else
		{
			/**
			 * Get the access token if it is provided by the facebook API.
			 * If any problem happens getting the access token then catch
			 * them with the catch block.
			 */
			try
			{
				$token = $helper->getAccessToken();
			}
			catch (Facebook\Exceptions\FacebookResponseException $e)
			{
				echo 'Graph API returns an error ' . $e->getMessage();
				$app->close();
			}
			catch (Facebook\Exceptions\FacebookSDKException $e)
			{
				echo 'Facebook SDK returns an error ' . $e->getMessage();
				$app->close();
			}

			/**
			 * If the token is not a long lived token then get a long lived
			 * token. A long lived token generally expires after 60 days.
			 */
			if (!$token->isLongLived())
			{
				try
				{
					$token = $oAuth2Client->getLongLivedAccessToken($token);
				}
				catch (Facebook\Exceptions\FacebookSDKException $e)
				{
					echo 'Facebook SDK returns an error ' . $e->getMessage();
					$app->close();
				}
			}
		}

		/**
		 * If the token generated then get the instagram user ID
		 */
		if (!empty($token))
		{
			try
			{
				$response     = $http->get("https://graph.facebook.com/v7.0/me/accounts?fields=connected_instagram_account&access_token=" . $token);
				$responseBody = json_decode($response->body);

				if ($response->code !== 200)
				{
					throw new Exception($responseBody->error->message);
				}

				if (!empty($responseBody->data))
				{
					$connected = $responseBody->data;

					foreach ($connected as $account)
					{
						if (isset($account->connected_instagram_account))
						{
							$igId = $account->connected_instagram_account->id;

							break;
						}
					}
				}
			}
			catch (Exception $e)
			{
				echo 'Facebook SDK returns an error ' . $e->getMessage();
				$app->close();
			}
		}

		$task = JVERSION < 4 ? 'config.save.component.apply' : 'component.apply';
		/**
		 * OnClick event while clicking on the Insert & Save button.
		 */
		$onClick = "
            window.opener.document.querySelector('.sppb-ig-token #access_token').value='" . $token . "';
            window.opener.document.querySelector('.sppb-ig-token #access_token').dispatchEvent(new Event('change'));
            window.opener.document.querySelector('.sppb-ig-token #ig_id').value='" . $igId . "';
            window.opener.document.querySelector('.sppb-ig-token #ig_id').dispatchEvent(new Event('change'));
            window.close();
        ";

		echo '<div class="wrapper" style="width: 100%;text-align: center;margin-top: 100px;word-break: break-word;">' .
            '<div><strong>Access Token: </strong>' . $token . '</div>' .
            '<div style="margin-top: 30px;"><strong>Instagram Id: </strong>' . $igId . '</div>' .
            '<button type="button" onclick="' . $onClick . '" class="btn btn-primary" style="cursor: pointer; margin-top: 50px;display: inline-block;font-weight: 400;color: #212529;text-align: center;vertical-align: middle;-webkit-user-select: none;-moz-user-select: none;-ms-user-select: none;user-select: none;background-color: transparent;border: 1px solid transparent;padding: 0.375rem 0.75rem;font-size: 1rem;line-height: 1.5;border-radius: 0.25rem;transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out,border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;background-color: #00d1b2;border-color: transparent;color: #fff;">Insert</button>' .
            '</div>';
		$app->close();
	}
}
