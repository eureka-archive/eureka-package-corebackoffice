<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Package\CoreBackoffice\Domain\User\Infrastructure\Mapper;

use Eureka\Package\CoreBackoffice\Domain\User\Repository\UserRepositoryInterface;

/**
 * DataMapper Mapper class for table "user_admin"
 *
 * @author Romain Cottard
 */
class UserMapper extends Abstracts\AbstractUserMapper implements UserRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function findByEmail($email)
    {
        return $this->findByKeys(['user_admin_email' => $email]);
    }

    /**
     * {@inheritdoc}
     */
    public function findByLogin($login)
    {
        return $this->findByEmail($login);
    }
}
