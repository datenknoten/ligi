<?php

namespace Datenknoten\UserCustomisationBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\Controller\ProfileController as BaseController;
use Symfony\Component\HttpFoundation\Request;
use Datenknoten\LigiBundle\Entity\File;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Model\UserInterface;

class ProfileController extends BaseController
{
    /**
     * Edit the user
     */
    public function editAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $request = $this->container->get('request');

        $form = $this->container->get('fos_user.profile.form');
        $form->setData($user);

        if ('POST' === $request->getMethod()) {
            $form->bind($request);
            if ($form->isValid()) {
                $em = $this->container->get('Doctrine')->getEntityManager();

                $uploadableManager = $this->container->get('stof_doctrine_extensions.uploadable.manager');
                $file = new File();
                $upload_info = $user->getAvatar();
                $uploadableManager->markEntityToUpload($file, $upload_info);
                $user->setAvatar($file);

                $em->persist($file);
                $em->persist($user);
                $em->flush();
            
                $this->setFlash('fos_user_success', 'profile.flash.updated');

                return new RedirectResponse($this->getRedirectionUrl($user));
            }
        }


        return $this->container->get('templating')->renderResponse(
            'FOSUserBundle:Profile:edit.html.'.$this->container->getParameter('fos_user.template.engine'),
            array('form' => $form->createView())
        );
    }    
}
