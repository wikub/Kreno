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

use App\DTO\EmailParams;
use App\Form\Admin\EmailParamsType;
use App\Form\Model\EmailParamsFormModel;
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

    public function __construct(GetParam $getParamService, SaveParam $saveParamService)
    {
        $this->getParamService = $getParamService;    
        $this->saveParamService = $saveParamService;
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
            $this->saveParams($emailParams);
        }

        return $this->render('admin/email_param/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    private function loadParams(): EmailParamsFormModel
    {
        $emailParams = new EmailParamsFormModel();
        
        foreach($emailParams as $key => $param) {
            $emailParams->$key = $this->getParamService->get($key);
        }
        
        return $emailParams;
    }

    private function saveParams(EmailParamsFormModel $emailParams): void
    {
        //$test = $this->saveParam; 
        foreach($emailParams as $key => $value) {
            ($this->saveParamService)($key, $value);
        }
    }
}
