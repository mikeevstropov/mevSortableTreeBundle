[![Packagist](https://img.shields.io/packagist/l/doctrine/orm.svg?maxAge=2592000)]()

# mevSortableTreeBundle
Offers a sortable feature for your Symfony2/3 admin tree listing

![screenshot](https://cloud.githubusercontent.com/assets/15070249/16892502/cd866138-4b3e-11e6-891d-02bf1ed4acac.png)

### Install requirements

**SonataAdminBundle**  
\- the SonataAdminBundle provides a installation article here:  
http://symfony.com/doc/current/cmf/tutorial/sonata-admin.html

**Install and enable Tree Extension from [gedmo/doctrine-extensions](https://packagist.org/packages/gedmo/doctrine-extensions)**  
\- nested behavior will implement the standard Nested-Set behavior on your Entity  
(check [stof/doctrine-extensions-bundle](https://symfony.com/doc/master/bundles/StofDoctrineExtensionsBundle/index.html) for easier integration in your project)
https://github.com/Atlantic18/DoctrineExtensions/blob/master/doc/tree.md

### Installation

```console
composer require mikeevstropov/sortable-tree-bundle
```

### Configuration

Enable the **mevSortableTreeBundle** to your kernel:

```php
// app/AppKernel.php

class AppKernel extends Kernel
{
	public function registerBundles()
	{
		$bundles = [
			// ...
			new Mev\SortableTreeBundle\MevSortableTreeBundle(),
		];
		// ...
	}
}
```

Include 'MevSortableTreeBundle:SortableBundle' to third service argument

``` yml
# app/config/services.yml

#   SonataAdminBundle Category
    admin.category:
        class: AppBundle\Admin\CategoryAdmin
        arguments:
            - ~
            - AppBundle\Entity\Category
            - 'MevSortableTreeBundle:SortableTree'
        tags:
            - { name: sonata.admin, manager_type: orm, label: Category }
```

Create new routes and the action field in Admin Class:

```php
// src/AppBundle/Admin/CategoryAdmin.php

// ...

use Sonata\AdminBundle\Route\RouteCollection;

class CategoryAdmin extends AbstractAdmin
{
	// ...
	protected function configureRoutes(RouteCollection $collection)
	{
		$collection->add('up', $this->getRouterIdParameter().'/up');
        $collection->add('down', $this->getRouterIdParameter().'/down');
    }
    
    protected function configureFormFields(FormMapper $formMapper)
    {
        // create custom query to hide the current element by `id`

        $subjectId = $this->getRoot()->getSubject()->getId();
        $query = null;

        if ($subjectId)
        {
            $query = $this->modelManager
                ->getEntityManager('AppBundle\Entity\Category')
                ->createQueryBuilder('c')
                ->select('c')
                ->from('AppBundle:Category', 'c')
                ->where('c.id != '. $subjectId);
        }
        
        // ...
        $formMapper->add('parent', 'sonata_type_model', array(
            'query' => $query,
            'required' => false, // remove this row after the root element is created
            'btn_add' => false,
            'property' => 'name'
        ));
    }

	protected function configureListFields(ListMapper $listMapper)
	{
		// ...
		$listMapper->add('_action', null, array(
			'actions' => array(
				'up' => array(
                    'template' => 'MevSortableTreeBundle:Default:list__action_up.html.twig'
                ),
                'down' => array(
                    'template' => 'MevSortableTreeBundle:Default:list__action_down.html.twig'
                )
			)
		));
	}
}
```

Configure sort the list of models by `root` and `lft` fields:

```php
// src/AppBundle/Admin/CategoryAdmin.php

// ...

class CategoryAdmin extends AbstractAdmin
{
	// ...
	public function createQuery($context = 'list')
	{
		$proxyQuery = parent::createQuery('list');
        // Default Alias is "o"
        // You can use `id` to hide root element
        // $proxyQuery->where('o.id != 1');
        $proxyQuery->addOrderBy('o.root', 'ASC');
        $proxyQuery->addOrderBy('o.lft', 'ASC');
    
		return $proxyQuery;
	}
	// ...
}
```

That's it!

### ToDo
- Sortable behaveor for root elements (but, you can hide root element)

