<?php

namespace App\Service;

use App\Entity\EmailTemplate;
use App\Repository\EmailTemplateRepository;
use Exception;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Validation;
use Twig\Environment;

class EmailSender 
{
    private EmailTemplateRepository $emailTemplateRepository;
    private Address $addressFrom;
    private MailerInterface $mailer;
    private Environment $twig;

    public function __construct(
        EmailTemplateRepository $emailTemplateRepository, 
        GetParam $getParam, 
        MailerInterface $mailer,
        Environment $twig
    )
    {
        $this->emailTemplateRepository = $emailTemplateRepository;
        $this->mailer = $mailer;
        $this->twig = $twig;

        if( $getParam->get('MAILER_FROM_EMAIL') == '' )
            throw new Exception('Le paramètrage de l\'adresse email de l\'expéditeur (MAILER_FROM_EMAIL) n\'est pas renseigné');

        $validator = Validation::createValidator();
        $violations = $validator->validate( $getParam->get('MAILER_FROM_EMAIL'), [new Email()]);
        
        if( count($violations) > 0 )
        {
            throw new Exception('Le paramètrage de l\'adresse email de l\'expéditeur (MAILER_FROM_EMAIL) est incorrect');
        }
        
        $this->addressFrom = new Address($getParam->get('MAILER_FROM_EMAIL'), $getParam->get('MAILER_FROM_NAME'));
        
    }


    public function getEmailTemplate(string $code): EmailTemplate
    {
        $emailTemplate = $this->emailTemplateRepository->findOneBy(['code' => $code]);

        if( $emailTemplate == null ) throw new Exception('Aucun template email trouvé avec le code "'.$code.'".');
        
        return $emailTemplate;
    }

    public function send(string $template, string $subject, Address $adressTo, array $context = [])
    {
        $email = new TemplatedEmail();
        $email->from($this->addressFrom);
        $email->to($adressTo);
        $email->subject($subject);
        $email->htmlTemplate($template);
        $email->context($context);

        $this->mailer->send($email);
    }

    public function sendWithEmailTemplate(string $code, Address $adressTo, array $context = [])
    {
        $emailTemplate = $this->getEmailTemplate($code);
        
        $email = new TemplatedEmail();
        $email->from($this->addressFrom);
        $email->to($adressTo);

        $subjectTwigTemplate = $this->twig->createTemplate($emailTemplate->getSubject());
        $email->subject($subjectTwigTemplate->render($context));
        
        $bodyTwigTemplate = $this->twig->createTemplate($emailTemplate->getBody());
        $email->html($bodyTwigTemplate->render($context));
        
        $this->mailer->send($email);
        
    }


    

}