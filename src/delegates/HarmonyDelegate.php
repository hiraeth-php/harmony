<?php

namespace Hiraeth\Harmony;

use Hiraeth;
use WoohooLabs\Harmony\Harmony;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

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
		$manager = $app->get(Hiraeth\Middleware\Manager::class);
		$harmony = new Harmony(
			$app->get(ServerRequestInterface::class),
			$app->get(ResponseInterface::class)
		);

		foreach ($manager->getAll() as $middleware){
			$harmony->addMiddleware($middleware);
		}

		return $harmony;
	}
}
