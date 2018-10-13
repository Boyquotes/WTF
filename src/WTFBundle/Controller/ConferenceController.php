<?php

namespace WTFBundle\Controller;

use WTFBundle\Entity\Conference;
use WTFBundle\Entity\User;
use WTFBundle\Entity\AbstractGMap;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\httpFoundation\File\File;
use Symfony\Component\httpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


/**
 * Conference controller.
 *
 */
class ConferenceController extends Controller
{
    /**
     * Lists all conference entities.
     *
     * @Route("/", name="conference_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $conferences = $em->getRepository('WTFBundle:Conference')->findAll();

        return $this->render('conference/index.html.twig', array(
            'conferences' => $conferences,
        ));
    }

    /**
     * Creates a new conference entity.
     *
     * @Route("/new", name="conference_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $conference = new Conference();
        $form = $this->createForm('WTFBundle\Form\ConferenceType', $conference);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $conference->setAuteur($this->getUser());
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file  **/
            $file=$conference->getImage();
            if($file instanceof UploadedFile){
                $fileName =md5(uniqid()).'.'.$file->guessExtension();
                $file->move($this->getParameter('image_directory'),$fileName);
            }
            $conference->setImage($fileName);
            $this->getDoctrine()->getManager()->flush();

            $em = $this->getDoctrine()->getManager();
            $em->persist($conference);
            $em->flush();

            return $this->redirectToRoute('conference_show', array('id' => $conference->getId()));
        }

        return $this->render('conference/new.html.twig', array(
            'conference' => $conference,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a conference entity.
     *
     * @Route("/{id}", name="conference_show", requirements={"id"="\d+"})
     * @Method("GET")
     */
    public function showAction(Conference $conference)
    {
        $currentUser = $this->getUser();
        if ($currentUser == $conference->getAuteur()) {
            $deleteForm = $this->createDeleteForm($conference);
            return $this->render('conference/showAuthor.html.twig', array(
                'conference' => $conference,
                'delete_form' => $deleteForm->createView(),
            ));
        }
        else{
            return $this->render('show.html.twig', array('conference' => $conference));
        }
    }

    /**
     * Displays a form to edit an existing conference entity.
     *
     * @Route("/{id}/edit", name="conference_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Conference $conference)
    {
        $currentUser = $this->getUser();
        if ($currentUser == $conference->getAuteur()) {
            $deleteForm = $this->createDeleteForm($conference);
            $editForm = $this->createForm('WTFBundle\Form\ConferenceType', $conference);
            $editForm->handleRequest($request);

            if ($editForm->isSubmitted() && $editForm->isValid()) {
                /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file * */
                $file = $conference->getImage();
                if ($file instanceof UploadedFile) {
                    $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                    $file->move($this->getParameter('image_directory'), $fileName);
                }
                $conference->setImage($fileName);
                $this->getDoctrine()->getManager()->flush();

                return $this->redirectToRoute('conference_show', array('id' => $conference->getId()));
            }

            return $this->render('conference/edit.html.twig', array(
                'conference' => $conference,
                'edit_form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            ));
        }
        else{
            throw new AccessDeniedException('Vous n\'avez pas accès à cette section.');
        }
    }

    /**
     * Deletes a conference entity.
     *
     * @Route("/{id}", name="conference_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Conference $conference)
    {
        $currentUser = $this->getUser();
        if ($currentUser == $conference->getAuteur()) {
            $form = $this->createDeleteForm($conference);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($conference);
                $em->flush();
            }
            return $this->redirectToRoute('conference_index');
        }
        else{
            throw new AccessDeniedException('Vous n\'avez pas accès à cette section.');
        }
    }

    /**
     * Creates a form to delete a conference entity.
     *
     * @param Conference $conference The conference entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Conference $conference)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('conference_delete', array('id' => $conference->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
