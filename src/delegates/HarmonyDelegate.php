<?php

namespace Hiraeth\Harmony;

use Hiraeth;
use WoohooLabs\Harmony\Harmony;
use Psr\Http\Message\ServerRequestInterface as ServerRequest;
use Psr\Http\Message\ResponseInterface as Response;

/**
 *
 */
class HarmonyDelegate implements Hiraeth\Delegate
{
	/**
	 * Get the class for which the delegate operates.
	 *
	 * @static
	 * @access public
	 * @return string The class for which the delegate operates
	 */
	static public function getClass(): string
	{
		return Harmony::class;
	}


	/**
	 * Get the instance of the class for which the delegate operates.
	 *
	 * @access public
	 * @param Hiraeth\Application $app The application instance for which the delegate operates
	 * @return object The instance of the class for which the delegate operates
	 */
	public function __invoke(Hiraeth\Application $app): object
	{
		$harmony    = new Harmony($app->get(ServerRequest::class), $app->get(Response::class));
		$middleware = $app->getConfig('*', 'middleware', []);

		usort($middleware, function($a, $b) {
			$a_priority = $a['priority'] ?? 50;
			$b_priority = $b['priority'] ?? 50;

			return $a_priority - $b_priority;
		});

		foreach ($middleware as $config) {
			if (!$config || $config['disabled'] ?? FALSE) {
				continue;
			}

			$harmony->addMiddleware($app->get($config['class']));
		}

		return $harmony;
	}
}
