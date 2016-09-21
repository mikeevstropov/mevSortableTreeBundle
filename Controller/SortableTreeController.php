<?php

namespace Mev\SortableTreeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sonata\AdminBundle\Controller\CRUDController;

class SortableTreeController extends CRUDController
{
    public function upAction(Request $request)
    {
    	$object = $this->admin->getSubject();
    	$entity = \Doctrine\Common\Util\ClassUtils::getClass($object);
		$id = $object->getId();
		
    	$repo = $this->getDoctrine()
    		->getManager()
    		->getRepository($entity);
		
    	$subject = $repo->findOneById($id);
		
    	if ($subject->getParent())
    	{
    		$repo->moveUp($subject);
    	}

        return new RedirectResponse($this->admin->generateUrl('list', $this->admin->getFilterParameters()));
    }
    public function downAction(Request $request)
    {
    	$object = $this->admin->getSubject();
    	$entity = \Doctrine\Common\Util\ClassUtils::getClass($object);
		$id = $object->getId();
		
    	$repo = $this->getDoctrine()
    		->getManager()
    		->getRepository($entity);
		
    	$subject = $repo->findOneById($id);
		
    	if ($subject->getParent())
    	{
    		$repo->moveDown($subject);
    	}

        return new RedirectResponse($this->admin->generateUrl('list', $this->admin->getFilterParameters()));
    }
}
