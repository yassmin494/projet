<?php

namespace App\Controller;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\TripDay;
use App\Entity\Payment;
use App\Form\PaymentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PaymentController extends AbstractController
{
   #[Route('/payment/{destination}', name: 'app_payment')]
public function payment(string $destination, EntityManagerInterface $em, Request $request): Response
{
    $tripDay = $em->getRepository(TripDay::class)->findOneBy(['destination' => $destination]);

    if (!$tripDay) {
        throw $this->createNotFoundException('Cette destination n’existe pas.');
    }

    // empty object - no pre-filled fields!
    $payment = new Payment();

    $form = $this->createForm(PaymentType::class, $payment);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {

        try {
            $payment->setAmount($tripDay->getPrice());
            $payment->setUser($this->getUser());
            $payment->setTripDay($tripDay);
            $payment->setCreateAt(new \DateTimeImmutable());

            $em->persist($payment);
            $em->flush();

            $this->addFlash('success', 'Votre paiement a été effectué avec succès !');
            return $this->redirectToRoute('app_success_payment');

        } catch (\Exception $e) {

            $this->addFlash('error', 'Le paiement a échoué. Veuillez réessayer.');
            return $this->redirectToRoute('app_payment', ['destination' => $destination]);
        }
    }

    return $this->render('payment/payment.html.twig', [
        'tripDay' => $tripDay,
        'form' => $form->createView(),
    ]);
}


#[Route('/oldreservations', name: 'app_old_reservations')]
public function oldReservations(ManagerRegistry $doctrine): Response
{
    $user = $this->getUser();
    
    $payments = $doctrine->getRepository(Payment::class)
        ->findBy(['user' => $user], ['createAt' => 'DESC']);

    return $this->render('payment/oldreservations.html.twig', [
        'payments' => $payments,
    ]);
}
}
