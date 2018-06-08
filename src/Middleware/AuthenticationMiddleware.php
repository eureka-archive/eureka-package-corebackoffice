<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Package\CoreBackoffice\Middleware;

use Eureka\Component\Config\Config;
use Eureka\Component\Http\Bag\Session;
use Eureka\Component\Orm\Exception\EntityNotExistsException;
use Eureka\Kernel\Http\Middleware\Exception\UnauthorizedException;
use Eureka\Psr\Http\Server\MiddlewareInterface;
use Eureka\Psr\Http\Server\RequestHandlerInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Add legacy constants & vars used by legacy code.
 * Will be removed when all legacy code will be removed.
 *
 * @author Romain Cottard
 */
class AuthenticationMiddleware implements MiddlewareInterface
{
    /** @var \Psr\Container\ContainerInterface $container */
    protected $container = null;

    /** @var \Eureka\Component\Config\Config config */
    protected $config = null;

    /**
     * ExceptionMiddleware constructor.
     *
     * @param \Psr\Container\ContainerInterface $container
     * @param \Eureka\Component\Config\Config $config
     */
    public function __construct(ContainerInterface $container, Config $config)
    {
        $this->container = $container;
        $this->config    = $config;
    }

    /**
     * @param  \Psr\Http\Message\ServerRequestInterface $request
     * @param  \Eureka\Psr\Http\Server\RequestHandlerInterface $handler
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler)
    {
        $route   = $request->getAttribute('route');
        $session = Session::getInstance();

        if (!in_array($route->getUri(), ['/user/login', '/user/logout'])) {

            try {

                $this->assertIsAuthenticated($session);

                try {
                    $userRepository = $this->container->get('user.repository');
                    $user    = $userRepository->findById($this->getSessionUserId($session));
                    $request = $request->withAttribute('currentUser', $user);
                } catch (EntityNotExistsException $exception) {
                    throw new UnauthorizedException('User not found !');
                }

            } catch (UnauthorizedException $exception) {
                /** @var \Eureka\Component\Routing\Route $route */
                $route  = $this->container->get('router')->get('user_login');
                header('Location: ' . $route->getUri());
                exit(0);
            }
        }

        return $handler->handle($request);
    }

    /**
     * @param  Session $session
     * @return void
     * @throws \Eureka\Kernel\Http\Middleware\Exception\UnauthorizedException
     */
    private function assertIsAuthenticated(Session $session)
    {
        $sessionName = $this->config->get('app.session.name');

        if (!$session->has($sessionName)) {
            throw new UnauthorizedException('Unauthorized access.', 401);
        }

        $acl = $session->get($sessionName);

        if (empty($acl) || empty($acl['user_id'])) {
            throw new UnauthorizedException('Unauthorized access.', 401);
        }
    }

    /**
     * @param \Eureka\Component\Http\Bag\Session $session
     * @return int
     */
    private function getSessionUserId(Session $session)
    {
        $sessionName = $this->config->get('app.session.name');

        $acl = $session->get($sessionName);

        return (int) $acl['user_id'];
    }
}
