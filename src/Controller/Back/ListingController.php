<?php

namespace App\Controller\Back;

use App\Entity\Listing;
use App\Form\ListingType;
use App\Repository\ListingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/listing', name:'app_listing_')]
class ListingController extends AbstractController
{
    public function __construct(private ListingRepository $listingRepository){

    }
    
    #[Route('/', name: 'index')]
    public function index(Request $request): Response
    {
        $lastSold = $this->listingRepository->findBy([],['createdAt' => 'DESC'] , 4);
        $mostRepresentatedBrand = $this->listingRepository->mostRepresentatedBrand();

        return $this->render('back/listing/index.html.twig', [
            'lastSold' => $lastSold,
            'mostRepre' => $mostRepresentatedBrand
        ]);
    }
}
