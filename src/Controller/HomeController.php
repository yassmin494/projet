<?php 

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\TripDay;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }

    #[Route('/offers', name: 'app_offers')]
    public function offers(ManagerRegistry $doctrine): Response
    {
        $tripDays = $doctrine->getRepository(TripDay::class)
            ->findBy([], ['destination' => 'ASC']);

        $offers = [];
        foreach ($tripDays as $day) {
            if (!isset($offers[$day->getDestination()])) {
                $offers[$day->getDestination()] = [
                    'destination' => $day->getDestination(),
                    'price' => $day->getPrice(),
                    'image' => $day->getImage(),
                ];
            }
        }

        return $this->render('home/offers.html.twig', [
            'offers' => $offers,
        ]);
    }

#[Route('/admindashboard', name: 'app_admindashboard')]
    public function admindashboard(): Response
    {
        return $this->render('home/admindashboard.html.twig');
    }

    
}
