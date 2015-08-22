<?php

namespace Datenknoten\LigiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;


/**
 *
 * search Controller
 *
 * @Route("/search")
 */
class SearchController extends Controller
{
    /**
     * @Route("/{search_type}/{search}",name="ligi_search", defaults={"search"=""})
     * @Template()
     */
    public function indexAction($search_type, $search, Request $request)
    {
        $_search = $request->request->get('search', null);
        if (!is_null($_search)) {
            return $this->redirect($this->generateUrl('ligi_search', ['search_type' => $search_type, 'search' => $_search]));
        }

        $search_term = ($search == '' ? '%' : $search);
        if (strpos($search_term, '%') === false) {
            $search_term = '%'.$search_term.'%';
        }
        
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select(array('i'))
            ->from('LigiBundle:Item', 'i')
            ->where('i.name LIKE :name')
            ->andWhere('i.description LIKE :description')
            ->andWhere('i.is_request = :is_request')
            ->orderBy('i.id', 'DESC')
            ->setParameter('name', $search_term)
            ->setParameter('description', $search_term)
            ->setParameter('is_request', ($search_type == 'request' ? true : false));
        $query = $qb->getQuery();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1)/*page number*/,
            12/*limit per page*/
        );
        
        return [
            "search_type" => $search_type,
            "search" => $search,
            "pagination" => $pagination,
        ];
    }

}
