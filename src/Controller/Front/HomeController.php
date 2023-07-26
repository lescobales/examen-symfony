<?php

namespace App\Controller\Front;

use App\Entity\Listing;
use App\Form\ListingType;
use App\Repository\ListingRepository;
use App\Service\Entity\ListingService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    public function __construct(private ListingRepository $listingRepository,
                                private ListingService $listingService){

    }
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $listings = $this->listingRepository->findBy([],['createdAt' => 'DESC'], 9);
        return $this->render('front/pages/home/index.html.twig', [
            'listings' => $listings
        ]);
    }

    #[Route('/annonce/all', name: 'app_home_all')]
    public function showAll(PaginatorInterface $paginator, Request $request): Response {
        $listings = $paginator->paginate(
            $this->listingRepository->getQb(),
            $request->query->getInt('page', 1),
            9
        );
        return $this->render('front/pages/listing/show-all.html.twig', [
            'listings' => $listings
        ]);
    }

    #[Route('/annonce/{id}', name: 'app_home_show')]
    public function show($id): Response {
        $listing = $this->listingRepository->find($id);
        return $this->render('front/pages/listing/show.html.twig', [
            'listing' => $listing
        ]);
    }
   

    #[Route('/nouvelle-annonce', name: 'app_home_add')]
    public function add(Request $request): Response
    {
        $listing = new Listing();
        $form = $this->createForm(ListingType::class, $listing);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->listingService->handleFileUpload($form->get('image')->getData(), $listing);
            $this->listingRepository->save($form->getData(), true);
        }
        return $this->render('front/forms/listing/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
