<?php

namespace Hiraeth\Harmony;

use Hiraeth;
use WoohooLabs\Harmony\Harmony;
use Psr\Http\Message\ServerRequestInterface as ServerRequest;
use Psr\Http\Message\ResponseInterface as Response;

/**
 *
 */
class Delegate implements Hiraeth\Delegate
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
		$harmony = new Harmony($broker->make(ServerRequest::class), $broker->make(Response::class));

		foreach ($this->app->getConfig('web', 'middleware.handlers', []) as $middleware) {
			$harmony->addMiddleware($broker->make($middleware));
		}

		return $harmony;
	}
}
