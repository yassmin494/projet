<?php

namespace App\Controller;

use App\Entity\UserInfo;
use App\Form\UserInfoType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function profile(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        // If user has no UserInfo yet, create one
        if (!$user->getUserInfo()) {
            $userInfo = new UserInfo();
            $userInfo->setUser($user);
        } else {
            $userInfo = $user->getUserInfo();
        }

        // Build form
        $form = $this->createForm(UserInfoType::class, $userInfo);
        $form->handleRequest($request);

        // If submitted: save
        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($userInfo);
            $em->flush();

            $this->addFlash('success', 'Profile updated!');
        }

        return $this->render('profile/profile.html.twig', [
            'form' => $form->createView(),
            'userInfo' => $userInfo
        ]);
    }
}
