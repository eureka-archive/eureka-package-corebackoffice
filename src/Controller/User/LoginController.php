<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Package\CoreBackoffice\Controller\User;

use Eureka\Component\Http\Bag\Session;
use Eureka\Package\CoreBackoffice\Controller\Common\AbstractController;
use Eureka\Package\CoreBackoffice\Domain\User\Exception\UserException;
use Eureka\Package\CoreBackoffice\Domain\User\Service\UserLogin;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class ProviderController
 *
 * @author Romain Cottard
 */
class LoginController extends AbstractController
{
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return \Eureka\Component\Http\Message\Response
     * @throws \Twig_Error
     * @throws \Exception
     */
    public function loginAction(ServerRequestInterface $request)
    {
        if ($request->getMethod() === 'POST') {
            // Get form
            $post = $request->getParsedBody();

            try {
                $this->assertValidForm($post);

                /** @var UserLogin $userLogin */
                $userLogin = $this->getContainer()->get('user.login');
                $userLogin->login($post['email'], $post['password'], Session::getInstance());

                //~ After valid login step, redirect to home
                $this->redirect($this->getRoute('home')->getUri());

            } catch (UserException $exception) {
                $this->addContext('error', ['email' => $exception->getMessage()]);
            }
        }

        return $this->renderResponse('@common/Content/Login.twig', $request);
    }

    /**
     * @param  \Psr\Http\Message\ServerRequestInterface $request
     * @return  void
     * @throws \Exception
     */
    public function logoutAction(ServerRequestInterface $request)
    {
        session_destroy();

        $this->redirect($this->getRoute('user_login')->getUri());

        return;
    }

    /**
     * @param  array $post
     * @return void
     * @throws \Eureka\Package\CoreBackoffice\Domain\User\Exception\UserException
     */
    private function assertValidForm($post)
    {
        if (empty($post['email']) || empty($post['password'])) {
            throw new UserException('Empty login or password');
        }
    }
}
