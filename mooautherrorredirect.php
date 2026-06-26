<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  System.mooautherrorredirect
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Uri\Uri;

class PlgSystemMooautherrorredirect extends CMSPlugin
{
	protected $app;
	private $watchingCallback = false;

	public function onAfterInitialise()
	{
		if (!$this->app) {
			$this->app = Factory::getApplication();
		}

		if (!$this->isMiniOrangeOAuthCallback()) {
			return;
		}

		$this->watchingCallback = true;
		ob_start();

		register_shutdown_function(array($this, 'redirectOnAutoCreationError'));
	}

	public function onAfterRender()
	{
		if (!$this->watchingCallback) {
			return;
		}

		$this->replaceAutoCreationErrorBody($this->app->getBody());
	}

	public function redirectOnAutoCreationError()
	{
		if (!$this->watchingCallback) {
			return;
		}

		$body = '';

		if (ob_get_level() > 0) {
			$body = ob_get_contents();
		}

		$this->replaceAutoCreationErrorBody($body);
	}

	private function replaceAutoCreationErrorBody($body)
	{
		if (!$this->isAutoCreationErrorPage($body)) {
			return;
		}

		while (ob_get_level() > 0) {
			ob_end_clean();
		}

		$this->sendRedirect();
	}

	private function isMiniOrangeOAuthCallback()
	{
		$requestUri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
		$path = trim((string) parse_url($requestUri, PHP_URL_PATH), '/');
		$callbackPath = 'v1/miniorangeoauth';

		return substr($path, -strlen($callbackPath)) === $callbackPath;
	}

	private function isAutoCreationErrorPage($body)
	{
		return strpos($body, 'User Auto-Creation Not Available in Current Plugin Version') !== false
			|| strpos($body, 'The plan could not create a new user during the login attempt') !== false;
	}

	private function sendRedirect()
	{
		$redirectUrl = trim((string) $this->params->get('redirect_url', '/'));

		if ($redirectUrl === '') {
			$redirectUrl = '/';
		}

		if (strpos($redirectUrl, 'http://') !== 0 && strpos($redirectUrl, 'https://') !== 0) {
			$redirectUrl = Uri::root() . ltrim($redirectUrl, '/');
		}

		if (!headers_sent()) {
			header('Location: ' . $redirectUrl, true, 303);
		}

		echo '<!DOCTYPE html><html><head><meta http-equiv="refresh" content="0;url=' . htmlspecialchars($redirectUrl, ENT_QUOTES, 'UTF-8') . '"></head><body></body></html>';
		exit;
	}
}
