<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Package\CoreBackoffice\Controller\Error;

use Eureka\Package\CoreBackoffice\Controller\Common\AbstractController;
use Psr\Http\Message\ServerRequestInterface;

class ErrorController extends AbstractController
{
    /**
     * @param  \Psr\Http\Message\ServerRequestInterface $request
     * @param  \Exception $exception
     * @return \Eureka\Component\Http\Message\Response
     * @throws \Twig_Error
     */
    public function page404(ServerRequestInterface $request, \Exception $exception = null)
    {
        return $this->renderResponse('@common/Content/Page404.twig', $request);
    }

    /**
     * @param  \Psr\Http\Message\ServerRequestInterface $request
     * @param  \Exception $exception
     * @return \Eureka\Component\Http\Message\Response
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Twig_Error
     */
    public function page500(ServerRequestInterface $request, \Exception $exception = null)
    {
        $this->addContext('exception', $exception);

        return $this->renderResponse('@common/Content/Page500.twig', $request);
    }
}
