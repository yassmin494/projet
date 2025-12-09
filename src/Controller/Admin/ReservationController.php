<?php
namespace App\Controller\Admin;

use App\Entity\Reservation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/reservation')]
#[IsGranted('ROLE_ADMIN')]
class ReservationController extends AbstractController
{
    #[Route('/', name:'admin_reservation_index')]
    public function index(EntityManagerInterface $em): Response
    {
        $reservations = $em->getRepository(Reservation::class)
            ->findBy([], ['createdAt'=>'DESC']);

        return $this->render('home/reservation_index.html.twig', [
            'reservations' => $reservations
        ]);
    }

    #[Route('/{id}/delete', name:'admin_reservation_delete', methods:['POST'])]
    public function delete(Reservation $reservation, Request $request, EntityManagerInterface $em): Response
    {
        if(!$this->isCsrfTokenValid('delete'.$reservation->getId(), $request->request->get('_token'))){
            return $this->redirectToRoute('admin_reservation_index');
        }

        $em->remove($reservation);
        $em->flush();
        $this->addFlash('success','Réservation supprimée !');

        return $this->redirectToRoute('admin_reservation_index');
    }
}
