<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use AppBundle\Form\CommentType;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class CommentController extends Controller
{
    /**
     * @Route("/create", name="comment_create")
     */
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $comment = new Comment();
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $comment->setUser($user);

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();
            // Débloquage du badge
            $this->get('event_dispatcher')->dispatch(\AppBundle\Event\CommentCreatedEvent::NAME, new \AppBundle\Event\CommentCreatedEvent($comment));

        }

        $comments = $em->getRepository('AppBundle:Comment')->findAll();
        $badges = $this->get('badge.manager')->getBadgeFor($user);

        return $this->render('comment/new.html.twig', [
            'comments' => $comments,
            'badges' => $badges,
            'form' => $form->createView()
        ]);
    }
}