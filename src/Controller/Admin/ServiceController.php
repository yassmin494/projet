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

        return $this->render('service/service_index.html.twig', [
            'services' => $services,
            'active_page' => 'Services', // pour menu actif
        ]);
    }

    #[Route('/add', name:'admin_service_add')]
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        $service = new Service();
        $form = $this->createForm(ServiceType::class, $service); // création -> is_edit = false
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('imageFile')->getData();

            if ($imageFile) {
                $newFilename = uniqid().'.'.$imageFile->guessExtension();
                try {
                    $imageFile->move(
                        $this->getParameter('uploads_directory'), // paramètre à configurer dans services.yaml
                        $newFilename
                    );
                } catch (FileException $e) {
                    // gérer l'erreur si nécessaire
                }
                $service->setImageFilename($newFilename);
            }

            $em->persist($service);
            $em->flush();
            $this->addFlash('success', 'Service ajouté !');

            return $this->redirectToRoute('admin_service_index');
        }

        return $this->render('service/service_add.html.twig', [
            'form' => $form->createView(),
            'active_page' => 'Services',
        ]);
    }

    #[Route('/{id}/edit', name:'admin_service_edit')]
    public function edit(Service $service, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ServiceType::class, $service, ['is_edit' => true]); // <-- correction ici
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('imageFile')->getData();

            if ($imageFile) {
                $newFilename = uniqid().'.'.$imageFile->guessExtension();
                try {
                    $imageFile->move(
                        $this->getParameter('uploads_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {}
                $service->setImageFilename($newFilename);
            }

            $em->flush();
            $this->addFlash('success', 'Service modifié !');

            return $this->redirectToRoute('admin_service_index');
        }

        return $this->render('service/service_add.html.twig', [
            'form' => $form->createView(),
            'active_page' => 'Services',
        ]);
    }

    #[Route('/{id}/delete', name:'admin_service_delete', methods:['POST'])]
    public function delete(Service $service, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$service->getId(), $request->request->get('_token'))) {
            $em->remove($service);
            $em->flush();
            $this->addFlash('success','Service supprimé !');
        }

        return $this->redirectToRoute('admin_service_index');
    }
}
