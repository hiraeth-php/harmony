<?php

namespace Hiraeth\Harmony;

use Hiraeth;
use WoohooLabs\Harmony\Harmony;

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
		$harmony = $app->get(Harmony::class);
		$manager = $app->get(Hiraeth\Middleware\Manager::class);

		foreach ($manager->getAll() as $middleware){
			$harmony->addMiddleware($middleware);
		}

		return $harmony;
	}
}
