<?php

namespace App\Controller;

use App\Service\RegistrationSerivce;
use Exception;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations as Rest;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/api/v1')]
#[OA\Tag(name: 'Auth')]
class RegistrationController extends AbstractFOSRestController
{
    public function __construct(private readonly RegistrationSerivce $registrationService)
    {
    }

    /**
     * @throws Exception
     */
    #[Rest\Post('/auth/signup', name: 'api_v1_signup_reader')]
    #[OA\Post(description: "Payload to create a Reader")]
    #[OA\RequestBody(
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                properties: [
                    new OA\Property(
                        property: 'username',
                        type: 'string',
                        example: 'John'
                    ),
                    new OA\Property(
                        property: 'email',
                        type: 'string',
                        pattern: '[^@\s]+@[^@\s]+\.[^@\s]+',
                        example: 'test@email.com'
                    ),
                    new OA\Property(
                        property: 'password',
                        type: 'string',
                        example: 'insert password'
                    ),
                ],
                example: [
                    'username' => 'John',
                    'email' => 'test@email.com',
                    'password' => 'insert password',
                ],
            )
        )
    )]
    #[OA\Response(
        response: Response::HTTP_NO_CONTENT,
        description: 'Successful operation',
    )]
    #[OA\Response(
        response: Response::HTTP_BAD_REQUEST,
        description: 'Invalid request body',
    )]
    public function register(Request $request): View
    {
        $this->registrationService->register($request->request->all());

        return View::create();
    }
}
