<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Package\CoreBackoffice\Domain\User\Service;

use Eureka\Component\Orm\Exception\EntityNotExistsException;
use Eureka\Package\CoreBackoffice\Domain\User\Entity\User;
use Eureka\Package\CoreBackoffice\Domain\User\Exception\AuthenticationFailedException;
use Eureka\Package\CoreBackoffice\Domain\User\Exception\UserNotFoundException;
use Eureka\Package\CoreBackoffice\Domain\User\Repository\UserRepositoryInterface;
use Eureka\Component\Http\Bag\Session;
use Eureka\Component\Password\Password;

/**
 * Class Edition
 *
 * @author Romain Cottard
 */
class UserLogin
{
    /** @var \Eureka\Package\CoreBackoffice\Domain\User\Repository\UserRepositoryInterface */
    protected $userRepository;

    /** @var \Eureka\Component\Password\Password */
    protected $passwordService;

    /**
     * Login constructor.
     *
     * @param \Eureka\Package\CoreBackoffice\Domain\User\Repository\UserRepositoryInterface $userRepository
     * @param \Eureka\Component\Password\Password $passwordService
     */
    public function __construct(UserRepositoryInterface $userRepository, Password $passwordService)
    {
        $this->userRepository  = $userRepository;
        $this->passwordService = $passwordService;
    }

    /**
     * @param  string $login
     * @param  string $password
     * @param  \Eureka\Component\Http\Bag\Session $session
     * @return \Eureka\Package\CoreBackoffice\Domain\User\Entity\User
     * @throws \Eureka\Package\CoreBackoffice\Domain\User\Exception\AuthenticationFailedException
     * @throws \Eureka\Package\CoreBackoffice\Domain\User\Exception\UserNotFoundException
     */
    public function login($login, $password, $session)
    {
        $userAuth = $this->findUser($login, $password);

        $this->setInSession($session, $userAuth);

        return $userAuth;
    }

    /**
     * @param  string $login
     * @param  string $password
     * @return \Eureka\Package\CoreBackoffice\Domain\User\Entity\User
     * @throws \Eureka\Package\CoreBackoffice\Domain\User\Exception\AuthenticationFailedException
     * @throws \Eureka\Package\CoreBackoffice\Domain\User\Exception\UserNotFoundException
     */
    private function findUser($login, $password)
    {
        $login    = trim($login);
        $password = trim($password);

        try {
            $user = $this->userRepository->findByLogin($login);
        } catch (EntityNotExistsException $exception) {
            throw new UserNotFoundException('Error with login or password.', 0, $exception);
        }

        if (!$this->passwordService->setPlain($password)->verify($user->getPassword())) {
            throw new AuthenticationFailedException('Error with login or password.');
        }

        return $user;
    }

    /**
     * @param  \Eureka\Component\Http\Bag\Session $session
     * @param  \Eureka\Package\CoreBackoffice\Domain\User\Entity\User $user
     * @return void
     */
    private function setInSession(Session $session, User $user)
    {
        // Session
        $session->set('hypheso', [
            'logged'  => 1,
            'user_id' => $user->getId(),
        ]);
    }
}
