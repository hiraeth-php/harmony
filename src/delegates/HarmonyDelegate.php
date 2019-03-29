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
	 *
	 */
	public function __construct(Hiraeth\Application $app)
	{
		$this->app = $app;
	}


	/**
	 * Get the instance of the class for which the delegate operates.
	 *
	 * @access public
	 * @param Hiraeth\Broker $broker The dependency injector instance
	 * @return object The instance of the class for which the delegate operates
	 */
	public function __invoke(Hiraeth\Broker $broker): object
	{
		$harmony    = new Harmony($broker->make(ServerRequest::class), $broker->make(Response::class));
		$middleware = $this->app->getConfig('*', 'middleware', []);

		uksort($middleware, function($a, $b) {
			$a_priority = $a['priority'] ?? 50;
			$b_priority = $b['priority'] ?? 50;

			return $a_priority - $b_priority;
		});

		foreach ($middleware as $path => $config) {
			if (!$config || $config['disabled'] ?? FALSE) {
				continue;
			}

			$harmony->addMiddleware($broker->make($config['class']));
		}

		return $harmony;
	}
}
