<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use OpenApi\Attributes as OA;

#[Route('/api/category', name: 'app_api_category_')]
final class CategoryController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $manager,
        private CategoryRepository $repository,
        private SerializerInterface $serializer,
        private UrlGeneratorInterface $urlGenerator
    ) {}

    #[Route(name: 'new', methods: 'POST')]
    #[OA\Post(
        path: '/api/category',
        summary: 'Création d\'une nouvelle catégorie',
        requestBody: new OA\RequestBody(
            required: true,
            description: 'Données de la catégorie à créer',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'title', type: 'string', example: 'Titre de la catégorie')
                ],
                type: 'object'
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Catégorie créée avec succès',
                headers: [
                    new OA\Header(
                        header: 'location',
                        description: 'URL pour consulter la catégorie créée',
                        schema: new OA\Schema(type: 'string')
                    )
                ],
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'id', type: 'integer', example: 1),
                        new OA\Property(property: 'title', type: 'string', example: 'Titre de la catégorie'),
                        new OA\Property(property: 'createdAt', type: 'string', format: 'date-time'),
                        new OA\Property(property: 'updatedAt', type: 'string', format: 'date-time'),
                        new OA\Property(property: 'menus', type: 'array', items: new OA\Items(type: 'string')),
                        new OA\Property(property: 'foods', type: 'array', items: new OA\Items(type: 'string')),
                    ],
                    type: 'object'
                )
            )
        ]
    )]
    public function new(Request $request): JsonResponse
    {
        $category = $this->serializer->deserialize($request->getContent(), Category::class, 'json');
        $category->setCreatedAt(new DateTimeImmutable());

        $this->manager->persist($category);
        $this->manager->flush();

        $responseData = $this->serializer->serialize($category, 'json');
        $location = $this->urlGenerator->generate(
            'app_api_category_show',
            ['id' => $category->getId()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        return new JsonResponse($responseData, Response::HTTP_CREATED, ['location' => $location], true);
    }

    #[Route('/{id}', name: 'show', methods: 'GET')]
    #[OA\Get(
        path: '/api/category/{id}',
        summary: 'Afficher une catégorie par son ID',
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID de la catégorie à afficher',
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Catégorie trouvée avec succès',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'id', type: 'integer', example: 1),
                        new OA\Property(property: 'title', type: 'string', example: 'Titre de la catégorie'),
                        new OA\Property(property: 'createdAt', type: 'string', format: 'date-time'),
                        new OA\Property(property: 'updatedAt', type: 'string', format: 'date-time'),
                        new OA\Property(property: 'menus', type: 'array', items: new OA\Items(type: 'string')),
                        new OA\Property(property: 'foods', type: 'array', items: new OA\Items(type: 'string')),
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Catégorie non trouvée'
            )
        ]
    )]
    public function show(int $id): JsonResponse
    {
        $category = $this->repository->findOneBy(['id' => $id]);

        if ($category) {
            $responseData = $this->serializer->serialize($category, 'json');

            return new JsonResponse($responseData, Response::HTTP_OK, [], true);
        }

        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }

    #[Route('/{id}', name: 'edit', methods: 'PUT')]
    #[OA\Put(
        path: '/api/category/{id}',
        summary: 'Modifier une catégorie par son ID',
        requestBody: new OA\RequestBody(
            required: true,
            description: 'Données de la catégorie à modifier',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'title', type: 'string', example: 'Titre de la catégorie modifié')
                ],
                type: 'object'
            )
        ),
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID de la catégorie à modifier',
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 201,
                description: 'Catégorie modifiée avec succès',
                headers: [
                    new OA\Header(
                        header: 'location',
                        description: 'URL pour consulter la catégorie modifiée',
                        schema: new OA\Schema(type: 'string')
                    )
                ],
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'id', type: 'integer', example: 1),
                        new OA\Property(property: 'title', type: 'string', example: 'Titre de la catégorie modifié'),
                        new OA\Property(property: 'createdAt', type: 'string', format: 'date-time'),
                        new OA\Property(property: 'updatedAt', type: 'string', format: 'date-time'),
                        new OA\Property(property: 'menus', type: 'array', items: new OA\Items(type: 'string')),
                        new OA\Property(property: 'foods', type: 'array', items: new OA\Items(type: 'string')),
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Catégorie non trouvé'
            )
        ]
    )]
    public function edit(int $id, Request $request): JsonResponse
    {
        $category = $this->repository->findOneBy(['id' => $id]);
        if ($category) {
            $category = $this->serializer->deserialize(
                $request->getContent(),
                Category::class,
                'json',
                [AbstractNormalizer::OBJECT_TO_POPULATE => $category]
            );
            $category->setUpdatedAt(new DateTimeImmutable());

            $this->manager->flush();

            $responseData = $this->serializer->serialize($category, 'json');
            $location = $this->urlGenerator->generate(
                'app_api_category_show',
                ['id' => $category->getId()],
                UrlGeneratorInterface::ABSOLUTE_URL
            );

            return new JsonResponse($responseData, Response::HTTP_CREATED, ['location' => $location], true);
        }

        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }

    #[Route('/{id}', name: 'delete', methods: 'DELETE')]
    #[OA\Delete(
        path: '/api/category/{id}',
        summary: 'Supprimer une catégorie par son ID',
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID due la catégorie à supprimer',
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 204,
                description: 'Catégorie supprimée avec succès'
            ),
            new OA\Response(
                response: 404,
                description: 'Catégorie non trouvée'
            )
        ]
    )]
    public function delete(int $id): JsonResponse
    {
        $category = $this->repository->findOneBy(['id' => $id]);

        if ($category) {
            $this->manager->remove($category);
            $this->manager->flush();

            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        }

        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }
}
