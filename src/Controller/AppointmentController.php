<?php

namespace App\Controller;

use App\Entity\Appointment;
use App\Form\AppointmentType;
use App\Repository\AppointmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/appointment')]
class AppointmentController extends AbstractController
{
    #[Route(name: 'app_appointment_index', methods: ['GET'])]
    public function index(AppointmentRepository $appointmentRepository): Response
    {
        // Récupérer les rendez-vous de l'utilisateur connecté
        $appointments = $appointmentRepository->findBy([
            'client' => $this->getUser(),
        ]);

        return $this->render('appointment/index.html.twig', [
            'appointments' => $appointments,
        ]);
    }

    #[Route('/new', name: 'app_appointment_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $appointment = new Appointment();
        $form = $this->createForm(AppointmentType::class, $appointment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Associer l'utilisateur connecté
            $appointment->setClient($this->getUser());

            $entityManager->persist($appointment);
            $entityManager->flush();

            return $this->redirectToRoute('app_appointment_index');
        }

        return $this->render('appointment/new.html.twig', [
            'appointment' => $appointment,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_appointment_show', methods: ['GET'])]
    public function show(Appointment $appointment): Response
    {
        // Vérifier que l'utilisateur est le propriétaire
        if ($appointment->getClient() !== $this->getUser()) {
            throw $this->createAccessDeniedException("Vous n'avez pas accès à ce rendez-vous.");
        }

        return $this->render('appointment/show.html.twig', [
            'appointment' => $appointment,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_appointment_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Appointment $appointment, EntityManagerInterface $entityManager): Response
    {
        // Vérifier que l'utilisateur est le propriétaire
        if ($appointment->getClient() !== $this->getUser()) {
            throw $this->createAccessDeniedException("Vous ne pouvez pas modifier ce rendez-vous.");
        }

        $form = $this->createForm(AppointmentType::class, $appointment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_appointment_index');
        }

        return $this->render('appointment/edit.html.twig', [
            'appointment' => $appointment,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_appointment_delete', methods: ['POST'])]
    public function delete(Request $request, Appointment $appointment, EntityManagerInterface $entityManager): Response
    {
        // Vérification du propriétaire et du token CSRF
        if ($appointment->getClient() !== $this->getUser()) {
            throw $this->createAccessDeniedException("Vous ne pouvez pas supprimer ce rendez-vous.");
        }

        if ($this->isCsrfTokenValid('delete' . $appointment->getId(), $request->request->get('_token'))) {
            $entityManager->remove($appointment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_appointment_index');
    }
}
