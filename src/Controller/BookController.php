<?php

namespace App\Controller;

use App\Entity\Book;
use App\Service\BookService;
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

#[OA\Tag(name: 'Book')]
#[Route('/api/v1')]
class BookController extends AbstractFOSRestController
{
    public function __construct(private readonly BookService $bookService)
    {
    }

    private const STATUS = ['available'];

    /**
     * Return a list of all books
     */
    #[Rest\Get('/books', name: 'api_v1_index_books')]
    #[OA\Get(description: "Return all books")]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Successful response',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(
                ref: new Model(
                    type: Book::class,
                    groups: ['main']
                )
            )
        )
    )]
    public function index(): View
    {
        return View::create($this->bookService->findAll());
    }

    /**
     * Return a single book
     */
    #[Rest\Get(path: '/books/{id}', name: 'api_v1_read_book')]
    #[OA\Get(description: "Return a book by its ID")]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Successful response',
        content: new Model(type: Book::class, groups: ['main'])
    )]
    #[OA\Response(
        response: Response::HTTP_NOT_FOUND,
        description: 'Book not found'
    )]
    public function read(Uuid $id): View
    {
        return View::create($this->bookService->find($id));
    }

    /**
     * Add a new book
     *
     * @throws Exception
     */
    #[Rest\Post('/books', name: 'api_v1_create_book')]
    #[OA\Post(description: "Create book")]
    #[OA\RequestBody(
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                properties: [
                    new OA\Property(
                        property: 'title',
                        type: 'string',
                        example: 'Latarnik'
                    ),
                    new OA\Property(
                        property: 'author',
                        type: 'string',
                        example: 'Henryk Sienkiewicz'
                    ),
                    new OA\Property(
                        property: 'publicationDate',
                        type: 'date',
                        example: '1881-01-01'
                    ),
                    new OA\Property(
                        property: 'status',
                        type: 'string',
                        enum: self::STATUS,
                        example: self::STATUS
                    ),
                ],
                example: [
                    'title' => 'Latarnik',
                    'author' => 'Henryk Sienkiewicz',
                    'publicationDate' => '1881-01-01',
                    'status' => 'available'
                ],
            )
        )
    )]
    #[OA\Response(
        response: Response::HTTP_CREATED,
        description: 'Successful operation',
        content: new Model(type: Book::class, groups: ['main'])
    )]
    #[OA\Response(
        response: Response::HTTP_BAD_REQUEST,
        description: 'Invalid request body'
    )]
    public function create(Request $request): View
    {
        return View::create($this->bookService->create($request->request->all()));
    }

    /**
     * Update an already existing book
     *
     * @throws Exception
     */
    #[Rest\Put(path: '/books/{id}', name: 'api_v1_update_book')]
    #[OA\Put(description: "Update a book by its ID")]
    #[OA\RequestBody(
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                properties: [
                    new OA\Property(
                        property: 'title',
                        type: 'string',
                        example: 'Latarnik'
                    ),
                    new OA\Property(
                        property: 'author',
                        type: 'string',
                        example: 'Henryk Sienkiewicz'
                    ),
                    new OA\Property(
                        property: 'publicationDate',
                        type: 'date',
                        example: '1881-01-01'
                    ),
                    new OA\Property(
                        property: 'status',
                        type: 'string',
                        example: 'available'
                    ),
                ],
                example: [
                    'title' => 'Latarnik',
                    'author' => 'Henryk Sienkiewicz',
                    'publicationDate' => '1881-01-01',
                    'status' => 'available'
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
    #[OA\Response(
        response: Response::HTTP_NOT_FOUND,
        description: 'Book not found'
    )]
    public function update(Uuid $id, Request $request): View
    {
        $this->bookService->update($id, $request->request->all());

        return View::create($this->bookService->find($id));
    }

    /**
     * Delete a single book
     */
    #[Rest\Delete(path: '/books/{id}', name: 'api_v1_delete_book')]
    #[OA\Delete(description: "Delete a book by its ID")]
    #[OA\Response(
        response: Response::HTTP_NO_CONTENT,
        description: 'Successful operation'
    )]
    #[OA\Response(
        response: Response::HTTP_NOT_FOUND,
        description: 'Book not found'
    )]
    public function delete(Uuid $id): View
    {
        $this->bookService->delete($id);

        return View::create();
    }
}
