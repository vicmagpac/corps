<?php

namespace Fgsl\Service;

use Zend\Authentication\AuthenticationService;
use Zend\EventManager\EventInterface;

class Authentication
{
    public static function verifyIdentity(EventInterface $event)
    {
        $application = $event->getApplication();
        $request = $application->getRequest();

        $uri = $request->getRequestUri();

        // separamos os segmentos do URL e recuperamos o último
        $segment = explode('/', $uri);
        $uri = end($segment);
        $baseUrl = str_replace('/', '', $request->getBaseUrl());
        $uri = empty($uri) ? $baseUrl : $uri;

        if ($uri == $baseUrl || $uri == 'login') {
            return;
        }

        $authentication = new AuthenticationService();
        if (!$authentication->hasIdentity()) {
            // preparemos um redirecionamento para a página de acesso ao sistema
            $response = $event->getResponse();
            $response->setHeaders($request->getHeaders()->addHeaderLine('Location', '/'));
            $response->setStatusCode(302);
            $response->sendHeaders();
            exit();
        }

    }
}