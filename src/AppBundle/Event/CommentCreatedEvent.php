<?php

namespace AppBundle\Event;

use AppBundle\Entity\Comment;
use Symfony\Component\Workflow\Event\Event;

class CommentCreatedEvent extends Event
{
    const NAME = 'app.comment_created';

    /**
     * @var Comment
     */
    private $comment;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * @return Comment
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param Comment $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }



}