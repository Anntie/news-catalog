<?php

namespace App\Controller;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/admin/api")
 */
class CategoriesController extends AbstractController
{
    /**
     * @Route("/categories", methods={"GET", "HEAD"})
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();

        return new JsonResponse([
            'data' => array_map(function (Category $category) {
                return $category->toArray();
            }, $categories)
        ]);
    }

    /**
     * @Route("/categories", methods={"POST"})
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     */
    public function create(
        Request $request,
        ValidatorInterface $validator,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $data = json_decode($request->getContent());

        $category = new Category();
        $category->setTitle($data->title);

        $errors = $validator->validate($category);
        if (count($errors) > 0) {
            return new JsonResponse([
                'errors' => (string) $errors
            ]);
        }

        $entityManager->persist($category);
        $entityManager->flush();

        return new JsonResponse([
            'data' => $category->toArray()
        ]);
    }

    /**
     * @Route("/categories/{id}", methods={"DELETE"})
     * @param int $id
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     */
    public function delete(
        int $id,
        EntityManagerInterface $entityManager
    ): JsonResponse
    {
        $category = $entityManager
            ->getRepository(Category::class)
            ->find($id);

        $entityManager->remove($category);
        $entityManager->flush();

        return new JsonResponse(['success' => true]);
    }
}