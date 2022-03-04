<?php

/*
 * This file is part of the Kreno package.
 *
 * (c) Valentin Van Meeuwen <contact@wikub.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
     * @Route("/", name="current", methods={"GET"})
     */
    public function current(WeekRepository $weekRepository): Response
    {
        $currentWeek = $weekRepository->findCurrent();

        if (null !== $currentWeek) {
            return $this->redirectToRoute('admin_week_show', ['id' => $currentWeek->getId()]);
        }

        $this->addFlash('notice', 'Aucune semaine n\'a été trouvé !');

        return $this->redirectToRoute('admin_week_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("{id}/next", name="next", methods={"GET"})
     */
    public function next(Week $week, WeekRepository $weekRepository): Response
    {
        $nextWeek = $weekRepository->findNext($week);

        if (null !== $nextWeek) {
            return $this->redirectToRoute('admin_week_show', ['id' => $nextWeek->getId()]);
        }

        $this->addFlash('notice', 'Aucune semaine n\'a été trouvé !');

        return $this->redirectToRoute('admin_week_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("{id}/previous", name="previous", methods={"GET"})
     */
    public function previous(Week $week, WeekRepository $weekRepository): Response
    {
        $previousWeek = $weekRepository->findPrevious($week);

        if (null !== $previousWeek) {
            return $this->redirectToRoute('admin_week_show', ['id' => $previousWeek->getId()]);
        }

        $this->addFlash('notice', 'Aucune semaine n\'a été trouvé !');

        return $this->redirectToRoute('admin_week_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/list", name="index", methods={"GET"})
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

            return $this->redirectToRoute('admin_week_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/week/new.html.twig', [
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
