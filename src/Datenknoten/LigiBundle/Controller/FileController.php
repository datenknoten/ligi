<?php

namespace Datenknoten\LigiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use Datenknoten\LigiBundle\Entity\File;

/**
 * Item controller.
 *
 * @Route("/file")
 */
class FileController extends Controller
{
    /**
     * @Route("/",name="ligi_file_new",options={"expose":true})
     * @Method("PUT")
     * @Security("has_role('ROLE_USER')")
     */
    public function indexAction(Request $request)
    {
        $content = json_decode($request->getContent());

        $em = $this->getDoctrine()->getManager();
        $uploadableManager = $this->get('stof_doctrine_extensions.uploadable.manager');
        
        $file = new File();

        $uploadableManager->markEntityToUpload($file, $this->convertDataToUploadedFile($content));
        $em->persist($file);
        $em->flush();

        $content->data = null;
        $content->id = $file->getId();
        $response = new Response(json_encode($content));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    private function convertDataToUploadedFile($data) {
        $file_content = str_replace('data:image/jpeg;base64,','',$data->data);
        $file_content = str_replace('data:image/gif;base64,','',$file_content);
        $file_content = str_replace('data:image/png;base64,','',$file_content);
        $file_content = base64_decode($file_content);
        $file_name = tempnam('/tmp','ligi');
        file_put_contents($file_name,$file_content);
        $retval = new UploadedFile($file_name,$data->name,null,strlen($file_content));
        return $retval;
    }
}
