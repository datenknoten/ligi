<?php

namespace Datenknoten\LigiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Datenknoten\LigiBundle\Entity\Item;
use Datenknoten\LigiBundle\Entity\File;
use Datenknoten\LigiBundle\Form\ItemType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Item controller.
 *
 * @Route("/item")
 */
class ItemController extends Controller
{

    /**
     * Lists all Item entities.
     *
     * @Route("/", name="ligi_item_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('LigiBundle:Item')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Item entity.
     *
     * @Route("/", name="ligi_item_create")
     * @Method("POST")
     * @Template("LigiBundle:Item:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Item();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $files = $entity->files;
            $em = $this->getDoctrine()->getManager();
            $entity->files = [];
            $em->persist($entity);
            $this->saveFiles($em,$entity,$files);
            $em->flush();

            return $this->redirect($this->generateUrl('ligi_item_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Item entity.
     *
     * @param Item $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Item $entity)
    {
        $form = $this->createForm(new ItemType(), $entity, array(
            'action' => $this->generateUrl('ligi_item_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Item entity.
     *
     * @Route("/new", name="ligi_item_new")
     * @Method("GET")
     * @Template()
     * @Security("has_role('ROLE_USER')")
     */
    public function newAction(Request $request)
    {
        $entity = new Item();
        $is_request = $request->query->get('is_request','false') == 'false' ? false : true;
        $entity->setIsRequest($is_request);
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Item entity.
     *
     * @Route("/{id}", name="ligi_item_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LigiBundle:Item')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Item entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Item entity.
     *
     * @Route("/{id}/edit", name="ligi_item_edit")
     * @Method("GET")
     * @Template()
     * @Security("has_role('ROLE_USER')")
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LigiBundle:Item')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Item entity.');
        }

        $user = $this->getUser();

        if ($user->getUsername() != $entity->getCreatedBy()) {
            $this->addFlash(
                'warning',
                'You can only edit your own items.'
            );

            return $this->redirect($this->generateUrl('ligi_item_show', ['id' => $entity->getId()]));
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Item entity.
    *
    * @param Item $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Item $entity)
    {
        $form = $this->createForm(new ItemType(), $entity, array(
            'action' => $this->generateUrl('ligi_item_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Item entity.
     *
     * @Route("/{id}", name="ligi_item_update")
     * @Method("PUT")
     * @Template("LigiBundle:Item:edit.html.twig")
     * @Security("has_role('ROLE_USER')")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LigiBundle:Item')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Item entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('ligi_item_show', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Item entity.
     *
     * @Route("/{id}", name="ligi_item_delete")
     * @Method("DELETE")
     * @Security("has_role('ROLE_USER')")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('LigiBundle:Item')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Item entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('ligi_item_index'));
    }

    /**
     * Creates a form to delete a Item entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('ligi_item_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }

    private function savefiles($em,$entity,$files) {
        $uploadableManager = $this->get('stof_doctrine_extensions.uploadable.manager');
        foreach ($files as $fileInfo) {
            $file = new File();
            $uploadableManager->markEntityToUpload($file, $fileInfo);
            $em->persist($file);
            $entity->files[] = $file;
            $em->persist($entity);
        }
    }
}
