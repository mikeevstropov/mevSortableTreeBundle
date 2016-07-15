<?php

namespace Mev\SortableTreeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sonata\AdminBundle\Controller\CRUDController;

class SortableTreeController extends CRUDController
{
    public function treeUpAction($page_id, Request $request)
    {
    	$object = $this->admin->getSubject();
    	$entity = \Doctrine\Common\Util\ClassUtils::getClass($object);
    	
    	$repo = $this->getDoctrine()
    		->getEntityManager()
    		->getRepository($entity);

    	$subject = $repo->findOneById($page_id);

    	if ($subject->getParent())
    	{
    		$repo->moveUp($page_id);
    	}

        return new RedirectResponse($this->admin->generateUrl('list', $this->admin->getFilterParameters()));
    }
    public function treeDownAction($page_id, Request $request)
    {
    	$object = $this->admin->getSubject();
    	$entity = \Doctrine\Common\Util\ClassUtils::getClass($object);
    	
    	$repo = $this->getDoctrine()
    		->getEntityManager()
    		->getRepository($entity);

    	$subject = $repo->findOneById($page_id);

    	if ($subject->getParent())
    	{
    		$repo->moveDown($subject);
    	}

        return new RedirectResponse($this->admin->generateUrl('list', $this->admin->getFilterParameters()));
    }
}
