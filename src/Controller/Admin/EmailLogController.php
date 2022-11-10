<?php

namespace App\Controller\Admin;

use App\Entity\EmailLog;
use App\Repository\EmailLogRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/email-log", name="admin_email_log_")
 */
class EmailLogController extends AbstractController
{
    private EmailLogRepository $emailLogRepository;

    public function __construct(EmailLogRepository $emailLogRepository)
    {
        $this->emailLogRepository = $emailLogRepository;    
    }

    /**
     * @Route("/", name="index")
     */
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        //$emailLogs = $this->emailLogRepository->findBy([], ['sendedAt' => 'DESC']);
        
        $emailLogs = $paginator->paginate(
            $this->emailLogRepository->getQueryBuilder(),
            $request->query->getInt('page', 1),
            50
        );

        return $this->render('admin/email_log/index.html.twig', [
            'email_logs' => $emailLogs,
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function show(EmailLog $emailLog): Response
    {
        return $this->render('admin/email_log/show.html.twig', [
            'email_log' => $emailLog,
        ]);
    }
}
