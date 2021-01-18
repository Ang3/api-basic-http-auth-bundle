<?php

namespace Ang3\Bundle\ApiBasicHttpAuthBundle\Security;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\DisabledException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Contracts\Translation\TranslatorInterface;

class BasicHttpAuthenticator extends AbstractGuardAuthenticator
{
    public const BASIC_AUTH_HEADER = 'Authorization';

    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(Request $request): bool
    {
        return $request->headers->has(self::BASIC_AUTH_HEADER);
    }

    /**
     * {@inheritdoc}
     */
    public function getCredentials(Request $request): array
    {
        $token = $request->headers->get(self::BASIC_AUTH_HEADER);

        if (!preg_match('#^(Basic |)(.*)$#', $token, $matches)) {
            return [];
        }

        $inlineCredentials = base64_decode($matches[2]);
        if (!$inlineCredentials) {
            return [];
        }

        $credentials = explode(':', utf8_encode($inlineCredentials));

        return [
            'username' => $credentials[0] ?? '',
            'api_key' => $credentials[1] ?? '',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getUser($credentials, UserProviderInterface $userProvider): UserInterface
    {
        return $userProvider->loadUserByUsername($credentials['username']);
    }

    /**
     * {@inheritdoc}
     */
    public function checkCredentials($credentials, UserInterface $user): bool
    {
        if (($user instanceof LockableUserInterface) && $user->isDisabled()) {
            $exception = new DisabledException('Account is disabled.');
            $exception->setUser($user);

            throw $exception;
        }

        if (!$user instanceof ApiUserInterface) {
            throw new \LogicException(sprintf('The user class "%s" must implements interface "%s" to be used with the basic authenticator.', get_class($user), ApiUserInterface::class));
        }

        return $credentials['api_key'] === $user->getApiKey();
    }

    /**
     * {@inheritdoc}
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey): ?Response
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $data = [
            'message' => $this->translator->trans($exception->getMessageKey(), $exception->getMessageData()),
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    /**
     * {@inheritdoc}
     */
    public function start(Request $request, AuthenticationException $authException = null): ?Response
    {
        $data = [
            'message' => 'Authentication Required',
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsRememberMe(): bool
    {
        return false;
    }
}
