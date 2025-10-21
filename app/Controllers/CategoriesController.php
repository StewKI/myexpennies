<?php

declare(strict_types=1);


namespace App\Controllers;

use App\Contracts\ValidatorFactoryInterface;
use App\ResponseFormatter;
use App\Services\CategoryService;
use App\Validators\CreateCategoryValidator;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Views\Twig;

class CategoriesController
{
    public function __construct(
        private readonly Twig $twig,
        private readonly ValidatorFactoryInterface $validatorFactory,
        private readonly CategoryService $categoryService,
        private readonly ResponseFormatter $responseFormatter,
    ) {}

    public function index(Request $request, Response $response): Response
    {
        $categories = $this->categoryService->getAll();

        return $this->twig->render($response, 'categories/index.twig', [
            'categories' => $categories,
        ]);
    }

    public function store(Request $request, Response $response): Response
    {
        $validator = $this->validatorFactory->make(CreateCategoryValidator::class);

        $data = $validator->validate($request->getParsedBody());

        $this->categoryService->create($data['name'], $request->getAttribute('user'));

        return $response->withHeader('Location', "/categories")->withStatus(302);
    }

    public function delete(Request $request, Response $response, array $args): Response
    {
        $this->categoryService->delete((int) $args['id']);

        return $response->withHeader('Location', "/categories")->withStatus(302);
    }

    public function get(Request $request, Response $response, array $args): Response
    {
        $category = $this->categoryService->getById((int) $args['id']);

        if (! $category) {
            return $response->withStatus(404);
        }

        $data = ['id' => $category->get_id(), 'name' => $category->get_name()];

        return $this->responseFormatter->asJson($response, $data);
    }
}