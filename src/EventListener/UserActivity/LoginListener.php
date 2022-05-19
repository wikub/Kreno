<?php

/*
 * This file is part of the Kreno package.
 *
 * (c) Valentin Van Meeuwen <contact@wikub.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\EventListener\UserActivity;

use App\Service\UserActivityAdd;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

class LoginListener
{
    private $userActivityAdd;
    private $user;

    public function __construct(UserActivityAdd $userActivityAdd, Security $security)
    {
        $this->userActivityAdd = $userActivityAdd;
        $this->user = $security->getUser();
    }

    public function onSymfonyComponentSecurityHttpEventLoginSuccessEvent(LoginSuccessEvent $event)
    {
        $this->userActivityAdd->add($this->user, $this->userActivityAdd::ACTION_LOGIN);
    }
}
