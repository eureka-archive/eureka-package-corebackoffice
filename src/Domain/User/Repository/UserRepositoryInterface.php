<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Package\CoreBackoffice\Domain\User\Repository;

use Eureka\Component\Orm\RepositoryInterface;

/**
 * Interface UserRepositoryInterface
 *
 * @author Romain Cottard
 */
interface UserRepositoryInterface extends RepositoryInterface
{
    /**
     * Find user by id.
     *
     * @param  int $id
     * @return \Eureka\Package\CoreBackoffice\Domain\User\Entity\User
     */
    public function findById($id);

    /**
     * Find user by email.
     *
     * @param  string $email
     * @return \Eureka\Package\CoreBackoffice\Domain\User\Entity\User
     */
    public function findByEmail($email);

    /**
     * Find user by login. Can be an alias for findByEmail() there is no login.
     *
     * @param  string $login
     * @return \Eureka\Package\CoreBackoffice\Domain\User\Entity\User
     */
    public function findByLogin($login);

    /**
     * {@inheritdoc}
     * @return \Eureka\Package\CoreBackoffice\Domain\User\Entity\User;
     */
    public function newEntity(\stdClass $row = null, $exists = false);
}
