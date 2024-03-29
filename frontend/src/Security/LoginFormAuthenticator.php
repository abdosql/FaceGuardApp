<?php

namespace App\Security;

use App\Repository\UserRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\CustomCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginFormAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;
    public function __construct(
        private UserRepository $userRepository,
        private RouterInterface $router,
        private Security $security,
    )
    {
    }
    public function authenticate(Request $request): Passport
    {
        $username = $request->request->get("username");
        $password = $request->request->get("password");
        $csrf_token = $request->request->get("_csrf_token");

        // Retrieve user from UserRepository
        $user = $this->userRepository->findOneBy(['username' => $username]);

        if (!$user) {
            throw new UserNotFoundException("User Not Found !");
        }

        if (!password_verify($password, $user->getPassword())) {
            throw new AuthenticationCredentialsNotFoundException('Invalid credentials.');
        }
        return new Passport(
            new UserBadge($username, function ($userIdentifier){
                $user = $this->userRepository->findOneBy(["username" => $userIdentifier]);
                if (!$user){
                    throw new UserNotFoundException("User Not Found !");
                }
                return $user;
            }),
            new CustomCredentials(function ($credentials, $user) {
                return password_verify($credentials, $user->getPassword());
            }, $password),
            [
                new CsrfTokenBadge("authenticate", $csrf_token),
                new RememberMeBadge()
            ]

        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($target = $this->getTargetPath($request->getSession(), $firewallName)){
            return new RedirectResponse($target);
        }
        return new RedirectResponse(
            $this->router->generate("app_dashboard_index")
        );
    }


    public function start(Request $request, ?AuthenticationException $authException = null): Response
    {
        return new RedirectResponse(
            $this->router->generate("app_login")
        );
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->router->generate("app_login");
    }
}
