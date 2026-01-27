<?php

namespace App\Controller;

use App\Entity\Food;
use App\Repository\FoodRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/food', name: 'app_api_food_')]
final class FoodController extends AbstractController
{
    public function __construct(private EntityManagerInterface $manager, private FoodRepository $repository) {}

    #[Route('/', name: 'new', methods: 'POST')]
    public function new(): Response
    {
        $food = new Food();
        $food->setTitle('Purée');
        $food->setDescription('Une purée d\'un goût exquis');
        $food->setCreatedAt(new DateTimeImmutable());
        $food->setPrice(1900);

        $this->manager->persist($food);
        $this->manager->flush();

        return $this->json(
            ['message' => 'Food ressource created with ' . $food->getId() . ' id'],
            Response::HTTP_CREATED
        );
    }

    #[Route('/{id}', name: 'show', methods: 'GET')]
    public function show(int $id): Response
    {
        $food = $this->repository->findOneBy(['id' => $id]);

        if (!$food) {
            throw $this->createNotFoundException('No food found for ' . $id . ' id');
        }
        return $this->json(
            ['message' => 'A food has been found : ' . $food->getTitle() . ' for ' . $food->getId() . ' id.']
        );
    }

    #[Route('/{id}', name: 'edit', methods: 'PUT')]
    public function edit(int $id): Response
    {
        $food = $this->repository->findOneBy(['id' => $id]);

        if (!$food) {
            throw $this->createNotFoundException('No food found for ' . $id . ' id');
        }

        $food->setTitle('Food name updated');
        $food->setUpdatedAt(new DateTimeImmutable());

        $this->manager->flush();

        return $this->redirectToRoute('app_api_food_show', ['id' => $food->getid()]);
    }

    #[Route('/{id}', name: 'delete', methods: 'DELETE')]
    public function delete(int $id): Response
    {
        $food = $this->repository->findOneBy(['id' => $id]);

        if (!$food) {
            throw $this->createNotFoundException('No food found for ' . $id . ' id');
        }

        $this->manager->remove($food);
        $this->manager->flush();

        return $this->json(['message' => 'Food ressource deleted'], Response::HTTP_NO_CONTENT);
    }
}
