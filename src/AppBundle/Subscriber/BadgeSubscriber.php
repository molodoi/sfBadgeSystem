<?php
namespace AppBundle\Subscriber;

use AppBundle\Event\CommentCreatedEvent;
use AppBundle\Mailer\AppMailer;
use Doctrine\Common\Persistence\ObjectManager;
use Ticme\BadgeBundle\Event\BadgeUnlockEvent;
use Ticme\BadgeBundle\Manager\BadgeManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class BadgeSubscriber implements EventSubscriberInterface {

    /**
     * @var AppMailer
     */
    private $mailer;

    /**
     * @var ObjectManager
     */
    private $em;
    /**
     * @var BadgeManager
     */
    private $badgeManager;

    public function __construct(AppMailer $mailer, ObjectManager $em, BadgeManager $badgeManager)
    {
        $this->mailer = $mailer;
        $this->em = $em;
        $this->badgeManager = $badgeManager;
    }

    /**
     * List all events we are subscribing to
     *
     * @return void
     */
    public static function getSubscribedEvents()
    {
        return [
            BadgeUnlockEvent::NAME => 'onBadgeUnlock',
            CommentCreatedEvent::NAME=> 'onNewComment'
        ];
    }

    /**
     * When a badge is unlocked we send an email
     *
     * @param BadgeUnlockEvent $event
     * @return void
     */
    public function onBadgeUnlock(BadgeUnlockEvent $event) {
        return $this->mailer->badgeUnlocked($event->getBadge(), $event->getUser());
    }

    /**
     * When a comment is created
     *
     * @param CommentCreatedEvent $event
     * @return void
     */
    public function onNewComment (CommentCreatedEvent $event) {
        $user = $event->getComment()->getUser();
        $comments_count = $this->em->getRepository('AppBundle:Comment')->countForUser($user->getId());
        $this->badgeManager->checkAndUnlock($user, 'comment', $comments_count);
    }

}