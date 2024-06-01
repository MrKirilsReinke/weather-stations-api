<?php
namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class AuthenticationEntryPoint implements AuthenticationEntryPointInterface
{
  public function start(Request $request, ?AuthenticationException $authException = null): JsonResponse
  {
    $data = [
      'message' => 'Access denied. You must provide a Bearer Token.',
      'error' => 'Authentication required'
    ];

    return new JsonResponse($data, JsonResponse::HTTP_UNAUTHORIZED);
  }
}