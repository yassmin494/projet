<?php

namespace App\Controller;
use Doctrine\ORM\EntityManagerInterface; 
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\User;


final class AdminClientController extends AbstractController
{
    #[Route('/adminclient', name: 'app_adminclient')]
    public function index(EntityManagerInterface $em): Response
    {
        // Fetch all users
        $users = $em->getRepository(User::class)->findAll();

        return $this->render('adminclient/adminclient.html.twig', [
            'users' => $users
        ]);
    }
}
