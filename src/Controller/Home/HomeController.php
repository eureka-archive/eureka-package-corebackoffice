<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Package\CoreBackoffice\Controller\Home;

use Eureka\Package\CoreBackoffice\Controller\Common\AbstractController;
use Psr\Http\Message\ServerRequestInterface;

class HomeController extends AbstractController
{
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return \Eureka\Component\Http\Message\Response
     * @throws \Twig_Error
     */
    public function index(ServerRequestInterface $request)
    {
        return $this->renderResponse('@template/Home/Home.twig', $request);
    }
}
