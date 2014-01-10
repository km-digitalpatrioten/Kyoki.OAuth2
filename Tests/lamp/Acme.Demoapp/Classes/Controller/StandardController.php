<?php
namespace Acme\Demoapp\Controller;

/*                                                                        *
 * This script belongs to the FLOW3 package "Acme.Demoapp".               *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use Acme\Demoapp\Command\InitCommandController as InitCommandController;

/**
 * Standard controller for the Acme.Demoapp package 
 *
 * @Flow\Scope("singleton")
 */
class StandardController extends \TYPO3\Flow\Mvc\Controller\ActionController {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Security\Context
	 */
	protected $securityContext;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Security\Authentication\AuthenticationManagerInterface
	 */
	protected $authenticationManager;

	/**
	 * Index action
	 *
	 * @return void
	 */
	public function indexAction() {
		$this->view->assign('username', InitCommandController::USERNAME);
		$this->view->assign('password', InitCommandController::PASSWORD);
		$this->view->assign('clientId', InitCommandController::CLIENT_ID);
		$this->view->assign('clientSecret',InitCommandController::CLIENT_SECRET);
	}

	public function loginAction() {

	}

	/**
	 * Authenticates an account by invoking the Provider based Authentication Manager.
	 *
	 * Los parametros vienen del formulario de login de la Home
	 *
	 * @return void
	 */
	public function authenticateAction() {

		try {
			$this->authenticationManager->authenticate();
		} catch (\TYPO3\Flow\Security\Exception\AuthenticationRequiredException $exception) {
			$this->flashMessageContainer->addMessage(new \TYPO3\Flow\Error\Message('Wrong username or password.'));
			$this->redirect('login');
		}
		if ($interceptedRequest = $this->securityContext->getInterceptedRequest()) {
			$this->redirect($interceptedRequest->getControllerActionName(),
				$interceptedRequest->getControllerName(),
				$interceptedRequest->getControllerPackageKey(),
				$interceptedRequest->getArguments());
		} else {
			$this->redirect('index');
		}

	}

}

?>