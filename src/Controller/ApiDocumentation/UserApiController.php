<?php

namespace App\Controller\ApiDocumentation;

use FOS\RestBundle\View\View;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;

#[Route(path: '/api')]
#[OA\Tag(name: 'Auth')]
class UserApiController
{
    #[Rest\Post(path: "/login_check", name: 'api_v1_login_user')]
    #[OA\Post(description: "User Login")]
    #[OA\RequestBody(
        description: "Payload to authenticate a User",
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "username", type: "string", example: "John"),
                new OA\Property(property: "email", type: "email", example: "test@email.com"),
                new OA\Property(property: "password", type: "string", example: "insert password")
            ]
        )
    )]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Return the token',
    )]
    public function login(): View
    {
        return View::create();
    }
}
