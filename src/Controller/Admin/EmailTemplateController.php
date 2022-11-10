<?php

namespace App\Controller\Admin;

use App\Entity\EmailTemplate;
use App\Form\Admin\EmailTemplateType;
use App\Repository\EmailTemplateRepository;
use App\Service\EmailSender;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/email-template", name="admin_email_template_")
 */
class EmailTemplateController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(EmailTemplateRepository $emailTemplateRepository, EmailSender $emailSender): Response
    {   
        return $this->render('admin/email_template/index.html.twig', [
            'email_templates' => $emailTemplateRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET", "POST"})
     */
    public function new(Request $request, EmailTemplateRepository $emailTemplateRepository): Response
    {
        $emailTemplate = new EmailTemplate();
        $form = $this->createForm(EmailTemplateType::class, $emailTemplate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $emailTemplateRepository->add($emailTemplate, true);

            return $this->redirectToRoute('admin_email_template_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/email_template/new.html.twig', [
            'email_template' => $emailTemplate,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, EmailTemplate $emailTemplate, EmailTemplateRepository $emailTemplateRepository): Response
    {
        $form = $this->createForm(EmailTemplateType::class, $emailTemplate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $emailTemplateRepository->add($emailTemplate, true);

            return $this->redirectToRoute('admin_email_template_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/email_template/edit.html.twig', [
            'email_template' => $emailTemplate,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"POST"})
     */
    public function delete(Request $request, EmailTemplate $emailTemplate, EmailTemplateRepository $emailTemplateRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$emailTemplate->getId(), $request->request->get('_token'))) {
            $emailTemplateRepository->remove($emailTemplate, true);
        }

        return $this->redirectToRoute('admin_email_template_index', [], Response::HTTP_SEE_OTHER);
    }
}
