<?php

namespace Cms\Controller;

use Zend\View\Model\ViewModel;

use Core\Controller\AbstractActionController;

class IndexController extends AbstractActionController
{
	public function indexAction()
	{
		if (!$this->hasPermission('cms', 'view')) {
			return $this->permissionDenied();
		}

		$view = new ViewModel();
		$view->setTemplate('cms/index');

		return $view;
	}

	public function pageAction()
	{
		/*if (!$this->hasPermission('cms/page', 'view')) {
			return $this->permissionDenied();
		}*/  //! @todo Add this to the ACL database.
		
		// Get the page from the request
		$page = $this->params('page');

		// Clean the input
		//! @note ZF2 seems to already do this for us, but better to be safe than sorry!
		$replacements = array(
			'%2E' => '.', '%2e' => '.', '%2F' => '/', '%2f' => '/',
			'%5C' => '\\', '%5c' => '\\', '%00' => ''
		);

		$page = str_replace(array_keys($replacements), $replacements, $page);
		
		// Set the template
		$template = 'cms/pages/' . $page;
		
		// Find the template resolver
		$serviceManager = $this->event->getApplication()->getServiceManager();
		$templatePathResolver = $serviceManager->get('TemplatePathResolver');

		// Ensure that Local File Inclusion protection is on for security
		$templatePathResolver->setLfiProtection(true);

		// Check if the template exists
		if (!$templatePathResolver->resolve($template)) {
			// Send a 404 error as the template didn't exist
			$this->getResponse()->setStatusCode(404);
		}
		
		// Render the template
		$view = new ViewModel();
		$view->setTemplate($template);

		return $view;
	}
}
