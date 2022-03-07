<?php

/*
 * This file is part of the Kreno package.
 *
 * (c) Valentin Van Meeuwen <contact@wikub.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Service;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Notifier\Notification\Notification;

class UserTimeslotApproachNotification
{
    private $em;
    private $userRepository;
    private $flash;
    private $mailer;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserRepository $userRepository,
        FlashBagInterface $flash,
        MailerInterface $mailer
    ) {
        $this->em = $entityManager;
        $this->userRepository = $userRepository;
        $this->flash = $flash;
        $this->mailer = $mailer;
    }

    public function send(): void
    {
        // Liste des utlisateurs qui vont avoir un créneau dans les 5 prochains jours
        $users = $this->userRepository->getWithTimeslotApproach();

        $nbEmailSend = 0;

        // Envoyer la notification
        foreach ($users as $user) {
            if (null === $user->getEmail()) {
                continue;
            }

            $email = (new TemplatedEmail())
                ->to($user->getEmail())
                ->subject('Votre prochain créneau est pour bientôt');

            $email->htmlTemplate('emails/user_next_timeslot.html.twig');
            $email->context([
                'user' => $user,
            ]);

            $this->mailer->send($email);
            ++$nbEmailSend;
        }

        if (1 === $nbEmailSend) {
            $this->flash->add('notice', 'un email a été envoyé');
        } elseif ($nbEmailSend > 1) {
            $this->flash->add('notice', $nbEmailSend.' emails ont été envoyé');
        } else {
            $this->flash->add('notice', 'aucun email a été envoyé');
        }
    }
}
