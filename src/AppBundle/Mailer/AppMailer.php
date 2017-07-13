<?php

namespace AppBundle\Mailer;
use AppBundle\Entity\User;
use Symfony\Component\Templating\EngineInterface;
use Ticme\BadgeBundle\Entity\Badge;

class AppMailer
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;
    /**
     * @var EngineInterface
     */
    private $template;

    public function __construct(\Swift_Mailer $mailer, EngineInterface $template)
    {
        $this->mailer = $mailer;
        $this->template = $template;
    }

    public function badgeUnlocked(Badge $badge, User $user){

        $message = \Swift_Message::newInstance()
            ->setSubject('Vous avez débloqué le badge'.$badge->getName())
            ->setTo($user->getEmail())
            ->setFrom('norply@doe.fr')
            ->setBody($this->template->render(
                'emails/badge.text.twig',[
                    'badge' => $badge,
                    'user' => $user
                ]
            ));
        return $this->mailer->send($message);


    }

}