<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Package\CoreBackoffice\Domain\User\Entity;

use Eureka\Component\Orm\EntityInterface;

/**
 * DataMapper Data class for table "user_admin"
 *
 * @author Romain Cottard
 */
class User extends Abstracts\AbstractUser implements EntityInterface
{
    /**
     * Get concatenation of first name & last name
     * @return string
     */
    public function getName()
    {
        return trim($this->getFirstname() . ' ' . $this->getLastname());
    }
}
