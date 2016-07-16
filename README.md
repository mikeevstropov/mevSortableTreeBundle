[![Packagist](https://img.shields.io/packagist/l/doctrine/orm.svg?maxAge=2592000)]()

# mevTreeSortableBehaviorBundle
Offers a sortable feature for your Symfony2/3 admin tree listing

![screenshot](https://cloud.githubusercontent.com/assets/15070249/16892502/cd866138-4b3e-11e6-891d-02bf1ed4acac.png)

### Install requirements

**SonataAdminBundle**  
\- the SonataAdminBundle provides a installation article here:  
http://symfony.com/doc/current/cmf/tutorial/sonata-admin.html

**StofDoctrineExtensionsBundle**  
\- then you need install StofDoctrineExtensionsBundle  
https://symfony.com/doc/master/bundles/StofDoctrineExtensionsBundle/index.html

**Enable Tree Extension**  
\- nested behavior will implement the standard Nested-Set behavior on your Entity  
https://github.com/Atlantic18/DoctrineExtensions/blob/master/doc/tree.md

### Configuration

Enable the **mevTreeSortableBehaviorBundle** to your kernel:

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

Create new routes and field action in Admin Class:

```php
// src/AppBundle/Admin/CategoryAdmin.php

// ...

use Sonata\AdminBundle\Route\RouteCollection;

class CategoryAdmin extends AbstractAdmin
{
	// ...
	protected function configureRoutes(RouteCollection $collection)
	{
		$collection->add('tree_up', $this->getRouterIdParameter().'/treeup/{page_id}');
		$collection->add('tree_down', $this->getRouterIdParameter().'/treedown/{page_id}');
    }

	protected function configureListFields(ListMapper $listMapper)
	{
		// ...
		$listMapper->add('_action', null, array(
			'actions' => array(
				'edit' => array(
					'template' => 'MevSortableTreeBundle:Default:tree_up_down.html.twig'
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
		$proxyQuery->addOrderBy('o.root', 'ASC');
		$proxyQuery->addOrderBy('o.lft', 'ASC');
    
		return $proxyQuery;
	}
	// ...
}
```

That's it!

### ToDo
- Sortable behaveor for root elements

