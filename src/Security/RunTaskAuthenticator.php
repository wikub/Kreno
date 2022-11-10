<?php

/*
 * This file is part of the Kreno package.
 *
 * (c) Valentin Van Meeuwen <contact@wikub.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Security;

use App\Service\GetParam;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\InMemoryUser;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class RunTaskAuthenticator extends AbstractAuthenticator
{
    private GetParam $getParam;

    public function __construct(GetParam $getParam)
    {
        $this->getParam = $getParam;
    }

    public function supports(Request $request): ?bool
    {
        return $request->query->has('t');
    }

    public function authenticate(Request $request): Passport
    {
        $runTaskToken = $request->query->get('t');
        if (null === $runTaskToken) {
            // The token header was empty, authentication fails with HTTP Status
            // Code 401 "Unauthorized"
            throw new CustomUserMessageAuthenticationException('No token provided');
        }

        $token = $this->getParam->get('RUN_TASK_TOKEN');
        if ('' === $token) {
            throw new CustomUserMessageAuthenticationException('Token has not been set');
        }

        if ($token !== $runTaskToken) {
            throw new CustomUserMessageAuthenticationException('Token is invalid');
        }

        return new SelfValidatingPassport(
            new UserBadge(
                $runTaskToken,
                function () {
                    return new InMemoryUser('run_task', null, ['ROLE_TASK']);
                }
            )
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // on success, let the request continue
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $data = [
            // you may want to customize or obfuscate the message first
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData()),

            // or to translate this message
            // $this->translator->trans($exception->getMessageKey(), $exception->getMessageData())
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }
}
