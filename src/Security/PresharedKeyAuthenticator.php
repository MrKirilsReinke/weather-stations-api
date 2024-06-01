<?php
namespace App\Security;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class PresharedKeyAuthenticator extends AbstractAuthenticator
{
  private $presharedKey;

  public function __construct(string $presharedKey) 
  {
    $this->presharedKey = $presharedKey;      
  }

  public function supports(Request $request): ?bool
  {
    return $request->headers->has('Authorization')
    && 0 === strpos($request->headers->get('Authorization'), 'Bearer ');
  }

  public function authenticate(Request $request): Passport
  {
    $authorizationHeader = $request->headers->get('Authorization');
    if (!$authorizationHeader || !$this->validateToken($authorizationHeader)) 
    {
      throw new CustomUserMessageAuthenticationException('Invalid authentication Bearer Token provided.');
    }

    return new SelfValidatingPassport(new UserBadge('dummy_user'));
  }

  private function validateToken($authorizationHeader): bool
  {
    $token = substr($authorizationHeader, 7);
    if (trim($token) === trim($this->presharedKey)) 
    {
      return true;
    } else 
    {
      error_log("Expected token: " . $this->presharedKey . ", Received token: " . $token);
      return false;
    }
  }

  public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
  {
    return null;
  }

  public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
  {
    $data = [
      'message' => strtr($exception->getMessageKey(), $exception->getMessageData()),
      'error' => 'Authentication failed'
    ];

    return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
  }
}



