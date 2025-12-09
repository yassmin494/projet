<?php
namespace App\Controller\Admin;

use App\Entity\Service;
use App\Form\ServiceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/admin/service')]
class ServiceController extends AbstractController
{
    #[Route('/', name:'admin_service_index')]
    public function index(EntityManagerInterface $em): Response
    {
        $services = $em->getRepository(Service::class)->findAll();
        return $this->render('admin/service/index.html.twig', ['services'=>$services]);
    }

    #[Route('/add', name:'admin_service_add')]
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        $service = new Service();
        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            // gérer upload image
            $imageFile = $form->get('imageFilename')->getData();
            if($imageFile){
                $newFilename = uniqid().'.'.$imageFile->guessExtension();
                try {
                    $imageFile->move(
                        $this->getParameter('services_directory'),
                        $newFilename
                    );
                    $service->setImageFilename($newFilename);
                } catch (FileException $e) { }
            }

            $em->persist($service);
            $em->flush();
            $this->addFlash('success','Service ajouté !');
            return $this->redirectToRoute('admin_service_index');
        }

        return $this->render('admin/service/add.html.twig', ['form'=>$form->createView()]);
    }
}
