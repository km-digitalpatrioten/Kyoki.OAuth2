<?php
namespace Kyoki\OAuth2\Controller;

/*                                                                        *
 * This script belongs to the Kyoki.OAuth2 package.                        *
 * @author Fernando Arconada <fernando.arconada@gmail.com>                *
 *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use Kyoki\OAuth2\Exception\OAuthException;

/**
 * Abstract class for controllers
 * It is used to manage exceptions
 *
 * @Flow\Scope("singleton")
 */
abstract class OAuthAbstractController extends \TYPO3\Flow\Mvc\Controller\ActionController {

	/**
	 * Mainly for managin extensions
	 *
	 * @param \TYPO3\Flow\Mvc\RequestInterface $request
	 * @param \TYPO3\Flow\Mvc\ResponseInterface $response
	 * @return void
	 */
	public function processRequest(\TYPO3\Flow\Mvc\RequestInterface $request, \TYPO3\Flow\Mvc\ResponseInterface $response) {
		try {
			parent::processRequest($request, $response);
		} catch (\TYPO3\Flow\Mvc\Exception\RequiredArgumentMissingException $ex) {
			$this->setErrorResponse($response,$ex->getMessage());
		} catch (OAuthException $ex) {
			$this->setErrorResponse($response,$ex->getMessage());
		} catch (\TYPO3\Flow\Property\Exception $ex) {
			$this->setErrorResponse($response,'Exception while property mapping');
		}
	}

	/**
	 * Sets error content in the response object
	 *
	 * @param ResponseInterface $response
	 * @param $message
	 */
	private function setErrorResponse(\TYPO3\Flow\Mvc\ResponseInterface $response, $message) {
		$response->setContent(json_encode(
			array(
				'error' => 'server_error',
				'error_message' => $message
			)));
		if ($response instanceof \TYPO3\Flow\Http\Response) {
			/**
			 * @var $response \TYPO3\Flow\Http\Response
			 */
			$response->setHeader('Content-Type', 'application/json');
			$response->setStatus(500);
		}

	}
}