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

use App\Form\Admin\EmailParamsType;
use App\Form\Model\EmailParamsFormModel;
use App\Repository\EmailTemplateRepository;
use App\Service\GetParam;
use App\Service\SaveParam;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/email/param", name="admin_email_param_")
 */
class EmailParamController extends AbstractController
{
    private GetParam $getParamService;
    private SaveParam $saveParamService;
    private EmailTemplateRepository $emailTemplateRepository;

    public function __construct(
        GetParam $getParamService,
        SaveParam $saveParamService,
        EmailTemplateRepository $emailTemplateRepository
    ) {
        $this->getParamService = $getParamService;
        $this->saveParamService = $saveParamService;
        $this->emailTemplateRepository = $emailTemplateRepository;
    }

    /**
     * @Route("/", name="index")
     */
    public function index(Request $request): Response
    {
        $emailParams = $this->loadParams();

        $form = $this->createForm(EmailParamsType::class, $emailParams);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (
                true === $emailParams->EMAIL_NOTIF_START_CYCLE_ENABLE
                && !$this->emailTemplateExist('EMAIL_NOTIF_START_CYCLE')
            ) {
                $emailParams->EMAIL_NOTIF_START_CYCLE_ENABLE = false;
                $this->addFlash('error', 'Notification début de cycle ne peut être activé, si le template associé n\'existe pas.');
            }

            if (
                true === $emailParams->EMAIL_NOTIF_START_CYCLE_ENABLE
                && !($emailParams->EMAIL_NOTIF_START_CYCLE_NB_DAYS_BEFORE > 0)
            ) {
                $emailParams->EMAIL_NOTIF_START_CYCLE_ENABLE = false;
                $this->addFlash('error', 'Notification début de cycle ne peut être activé, si nombre de jour n\'est pas supérieur à 0.');
            }

            if (
                true === $emailParams->EMAIL_NOTIF_REMINDER_TIMESLOT_ENABLE
                && !$this->emailTemplateExist('EMAIL_NOTIF_REMINDER_TIMESLOT')
            ) {
                $emailParams->EMAIL_NOTIF_REMINDER_TIMESLOT_ENABLE = false;
                $this->addFlash('error', 'La notification de rappel de créneaux ne peut être activé, si le template associé n\'existe pas.');
            }

            if (
                true === $emailParams->EMAIL_NOTIF_REMINDER_TIMESLOT_ENABLE
                && !($emailParams->EMAIL_NOTIF_REMINDER_TIMESLOT_NB_HOURS_BEFORE > 0)
            ) {
                $emailParams->EMAIL_NOTIF_REMINDER_TIMESLOT_ENABLE = false;
                $this->addFlash('error', 'La notification de rappel de créneaux ne peut être activé, si le nombre d\'heure n\'est pas supérieur à 0.');
            }

            $this->saveParams($emailParams);
            $form = $this->createForm(EmailParamsType::class, $emailParams);
        }

        return $this->render('admin/email_param/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    private function loadParams(): EmailParamsFormModel
    {
        $emailParams = new EmailParamsFormModel();

        foreach (array_keys(get_class_vars(\get_class($emailParams))) as $key) {
            $emailParams->$key = $this->getParamService->get($key);
        }

        return $emailParams;
    }

    private function saveParams(EmailParamsFormModel $emailParams): void
    {
        foreach (array_keys(get_class_vars(\get_class($emailParams))) as $key) {
            ($this->saveParamService)($key, $emailParams->$key);
        }
    }

    private function emailTemplateExist(string $code): bool
    {
        if (1 === $this->emailTemplateRepository->count(['code' => $code])) {
            return true;
        }

        return false;
    }
}
