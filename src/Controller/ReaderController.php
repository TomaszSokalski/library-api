<?php

namespace App\Controller;

use App\Entity\Reader;
use App\Service\ReaderService;
use Exception;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

#[OA\Tag(name: 'Reader')]
#[Route('/api/v1')]
class ReaderController extends AbstractFOSRestController
{
    public function __construct(private readonly ReaderService $readerService)
    {
    }

    /**
     * Return a list of all readers
     */
    #[Rest\Get('/readers', name: 'api_v1_index_readers')]
    #[OA\Get(description: "Return all readers")]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Successful response',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Reader::class, groups: ['main']))
        )
    )]
    public function index(): View
    {
        return View::create($this->readerService->findAll());
    }

    /**
     * Return a single reader
     */
    #[Rest\Get(path: '/readers/{id}', name: 'api_v1_read_readers')]
    #[OA\Get(description: "Return a reader by its ID")]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Successful response',
        content: new Model(type: Reader::class, groups: ['main'])
    )]
    #[OA\Response(
        response: Response::HTTP_NOT_FOUND,
        description: 'Reader not found'
    )]
    public function read(Uuid $id): View
    {
        return View::create($this->readerService->find($id));
    }

    /**
     * Add a new reader
     *
     * @throws Exception
     */
    #[Rest\Post('/readers', name: 'api_v1_create_reader')]
    #[OA\Post(description: "Create reader")]
    #[OA\RequestBody(
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                properties: [
                    new OA\Property(
                        property: 'firstName',
                        type: 'string',
                        example: 'John'
                    ),
                    new OA\Property(
                        property: 'lastName',
                        type: 'string',
                        example: 'Doe'
                    ),
                    new OA\Property(
                        property: 'email',
                        type: 'string',
                        pattern: '[^@\s]+@[^@\s]+\.[^@\s]+',
                        example: 'john@doe.com'
                    ),
                ],
                example: [
                    'firstName' => 'John',
                    'lastName' => 'Doe',
                    'email' => 'john@doe.com',
                ],
            )
        )
    )]
    #[OA\Response(
        response: Response::HTTP_CREATED,
        description: 'Successful operation',
        content: new Model(type: Reader::class, groups: ['main'])
    )]
    #[OA\Response(
        response: Response::HTTP_BAD_REQUEST,
        description: 'Invalid request body'
    )]
    public function create(Request $request): View
    {
        return View::create($this->readerService->create($request->request->all()));
    }

    /**
     * Update an already existing reader
     *
     * @throws Exception
     */
    #[Rest\Put(path: '/readers/{id}', name: 'api_v1_update_reader')]
    #[OA\Put(description: "Update a reader by its ID")]
    #[OA\RequestBody(
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                properties: [
                    new OA\Property(
                        property: 'firstName',
                        type: 'string',
                        example: 'John'
                    ),
                    new OA\Property(
                        property: 'lastName',
                        type: 'string',
                        example: 'Doe'
                    ),
                    new OA\Property(
                        property: 'email',
                        type: 'string',
                        pattern: '[^@\s]+@[^@\s]+\.[^@\s]+',
                        example: 'john@doe.com'
                    ),
                ],
                example: [
                    'firstName' => 'John',
                    'lastName' => 'Doe',
                    'email' => 'john@doe.com',
                ],
            )
        )
    )]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Successful operation',
    )]
    #[OA\Response(
        response: Response::HTTP_BAD_REQUEST,
        description: 'Invalid request body',
    )]
    #[OA\Response(
        response: Response::HTTP_NOT_FOUND,
        description: 'Reader not found'
    )]
    public function update(Uuid $id, Request $request): View
    {
        $this->readerService->update($id, $request->request->all());

        return View::create($this->readerService->find($id));
    }

    /**
     * Delete a single reader
     */
    #[Rest\Delete(path: '/readers/{id}', name: 'api_v1_delete_reader')]
    #[OA\Delete(description: "Delete a reader by its ID")]
    #[OA\Response(
        response: Response::HTTP_NO_CONTENT,
        description: 'Successful operation'
    )]
    #[OA\Response(
        response: Response::HTTP_NOT_FOUND,
        description: 'Reader not found'
    )]
    public function delete(Uuid $id): View
    {
        $this->readerService->delete($id);

        return View::create();
    }

    /**
     * Add borrowed book to reader
     * @throws \Doctrine\DBAL\Exception
     */
    #[Rest\Put(path: '/readers/borrow/{id}', name: 'api_v1_borrow_book')]
    #[OA\Put(description: "Borrow book")]
    #[OA\RequestBody(
        description: "Json to borrow a book",
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: "borrowBook",
                    type: "string",
                    example: "ID of the book you want to borrow"
                ),
            ]
        )
    )]
    #[OA\Response(
        response: Response::HTTP_CREATED,
        description: 'Return the Reader ID',
    )]
    #[OA\Response(
        response: Response::HTTP_BAD_REQUEST,
        description: 'Invalid arguments',
    )]
    public function borrowBook(Request $request, Uuid $id): View
    {
        $this->readerService->borrowBook($request->request->get('borrowBook'), $id);

        return View::create();
    }
}
