<?php

namespace App\Controller\Admin;

use App\Entity\Week;
use App\Form\WeekType;
use App\Repository\WeekRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/week", name="admin_week_")
 */
class WeekController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(WeekRepository $weekRepository): Response
    {
        return $this->render('admin/week/index.html.twig', [
            'weeks' => $weekRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $week = new Week();
        $form = $this->createForm(WeekType::class, $week);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($week);
            $entityManager->flush();

            return $this->redirectToRoute('week_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('week/new.html.twig', [
            'week' => $week,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function show(Week $week): Response
    {
        return $this->render('admin/week/show.html.twig', [
            'week' => $week,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Week $week, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(WeekType::class, $week);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('week_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/week/edit.html.twig', [
            'week' => $week,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"POST"})
     */
    public function delete(Request $request, Week $week, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$week->getId(), $request->request->get('_token'))) {
            $entityManager->remove($week);
            $entityManager->flush();
        }

        return $this->redirectToRoute('week_index', [], Response::HTTP_SEE_OTHER);
    }
}
