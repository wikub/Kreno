<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\JobRegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/job/registration", name="job_registration_")
 */
class JobRegistrationController extends AbstractController
{
    /**
     * @Route("/user/{user}/new", name="new_foruser")
     */
    public function new_foruser(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(JobRegistrationType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $job = $data['job'];
            $job->setUser($user);

            $entityManager->flush();

            return $this->redirectToRoute('user_show', ['id' => $user->getId()]);
        }

        return $this->renderForm('job_registration/new_foruser.html.twig', [
            'form' => $form,
        ]);

    }
}
