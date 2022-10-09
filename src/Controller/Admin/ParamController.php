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

use App\Entity\Param;
use App\Form\Admin\ParamType;
use App\Repository\ParamRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/param", name="admin_param_")
 */
class ParamController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(ParamRepository $paramRepository): Response
    {
        return $this->render('admin/param/index.html.twig', [
            'params' => $paramRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET", "POST"})
     */
    public function new(Request $request, ParamRepository $paramRepository): Response
    {
        $param = new Param();
        $form = $this->createForm(ParamType::class, $param);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $paramRepository->add($param, true);

            return $this->redirectToRoute('admin_param_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/param/new.html.twig', [
            'param' => $param,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function show(Param $param): Response
    {
        return $this->render('admin/param/show.html.twig', [
            'param' => $param,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Param $param, ParamRepository $paramRepository): Response
    {
        $form = $this->createForm(ParamType::class, $param);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $paramRepository->add($param, true);

            return $this->redirectToRoute('admin_param_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/param/edit.html.twig', [
            'param' => $param,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"POST"})
     */
    public function delete(Request $request, Param $param, ParamRepository $paramRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$param->getId(), $request->request->get('_token'))) {
            $paramRepository->remove($param, true);
        }

        return $this->redirectToRoute('admin_param_index', [], Response::HTTP_SEE_OTHER);
    }
}
