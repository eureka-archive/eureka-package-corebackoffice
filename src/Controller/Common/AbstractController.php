<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Package\CoreBackoffice\Controller\Common;

use Eureka\Package\Core\Component\Controller\CoreController;
use Psr\Http\Message\ServerRequestInterface;

abstract class AbstractController extends CoreController
{
    /**
     * @param  $template
     * @param  \Psr\Http\Message\ServerRequestInterface $request
     * @param  array $context
     * @return \Eureka\Component\Http\Message\Response|string
     * @throws \Twig_Error
     */
    protected function renderResponse($template, ServerRequestInterface $request, array $context = [])
    {
        $cookieMenuStateName = $this->getConfig()->get('app.meta.cookie.menu_state_name');

        //~ Add current user to template context.
        $this->addContext('currentUser', $request->getAttribute('currentUser'));
        $this->addContext('menu_state', isset($_COOKIE[$cookieMenuStateName]) ? $_COOKIE[$cookieMenuStateName] : 'closed');

        return $this->getResponse($this->render($template, $context));
    }
}
