<?php

namespace App\Controller\Back;

use App\Entity\Model;
use App\Form\ModelType;
use App\Repository\ModelRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/model', name: 'app_model_')]
class ModelController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(ModelRepository $modelRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $models = $paginator->paginate(
            $modelRepository->getQb(),
            $request->query->getInt('page', 1),
            9
        );
        return $this->render('back/model/index.html.twig', [
            'models' => $models,
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $model = new Model();
        $form = $this->createForm(ModelType::class, $model);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //$modelService->handleFileUpload($form->get('image')->getData(), $category);
            $entityManager->persist($model);
            $entityManager->flush();

            return $this->redirectToRoute('app_model_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/model/new.html.twig', [
            'model' => $model,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Model $model): Response
    {
        return $this->render('back/model/show.html.twig', [
            'model' => $model,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Model $model, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ModelType::class, $model);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_model_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/model/edit.html.twig', [
            'model' => $model,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Model $model, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$model->getId(), $request->request->get('_token'))) {
            $entityManager->remove($model);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_model_index', [], Response::HTTP_SEE_OTHER);
    }
}
