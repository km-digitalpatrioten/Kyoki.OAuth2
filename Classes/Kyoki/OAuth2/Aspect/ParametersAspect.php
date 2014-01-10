<?php
namespace Kyoki\OAuth2\Aspect;

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
 * This Aspect is responsible os some parameters validation, specially related to grant types
 * @Flow\Aspect
 */
class ParametersAspect {

	/**
	 * @see http://tools.ietf.org/html/draft-ietf-oauth-v2-28
	 */
	const GRANTTYPE_REFRESHTOKEN = 'refresh_token';

	/**
	 *  @see http://tools.ietf.org/html/draft-ietf-oauth-v2-28
	 */
	const GRANTTYPE_AUTHCODE = 'authorization_code';

	/**
	 * @var \TYPO3\Flow\Security\Context
	 * @Flow\Inject
	 */
	protected $securityContext;

	/**
	 * PointCut token endpoint
	 * OAuth Token endpoint
	 *
	 * @Flow\Pointcut("method(Kyoki\OAuth2\Controller\TokenController->tokenAction())")
	 * @return void
	 */
	public function tokenParameters() {
	}

	/**
	 * PointCut token endpoint
	 * OAuth access grant endpoint
	 *
	 * @Flow\Pointcut("method(Kyoki\OAuth2\Controller\OAuthController->grantAction())")
	 * @return void
	 */
	public function allOAuthControllerMethods() {
	}

	/**
	 * Validates that the grant_type is refresh_token or authorization_code
	 * If grant_type then code parameter should be set
	 *
	 * @param \TYPO3\Flow\Aop\JoinPointInterface $joinPoint
	 * @return void
	 * @Flow\Before("Kyoki\OAuth2\Aspect\ParametersAspect->tokenParameters")
	 */
	public function validateTokenParameters(\TYPO3\Flow\Aop\JoinPointInterface $joinPoint) {
		$arguments = $joinPoint->getMethodArguments();

		if ($arguments['grant_type'] !== self::GRANTTYPE_REFRESHTOKEN && $arguments['grant_type'] !== self::GRANTTYPE_AUTHCODE) {
			throw new OAuthException('unsupported grant type', 1338317418);
		}

		if ($arguments['grant_type'] == self::GRANTTYPE_AUTHCODE && !isset($arguments['code'])) {
			throw new OAuthException('code not set', 1338317419);
		}
	}

	/**
	 * Validates that you are only passing your own OAuthCodes as parameters
	 *
	 * @param \TYPO3\Flow\Aop\JoinPointInterface $joinPoint
	 * @return void
	 * @Flow\Before("Kyoki\OAuth2\Aspect\ParametersAspect->allOAuthControllerMethods")
	 */
	public function validateOAuthCode(\TYPO3\Flow\Aop\JoinPointInterface $joinPoint) {
		if ($joinPoint->isMethodArgument('code')) {
			/**
			 * @var $code \Kyoki\OAuth2\Domain\Model\OAuthCode;
			 */
			$code = $joinPoint->getMethodArgument('code');
			if ($this->securityContext->getAccount() !== $code->getAccount()) {
				throw new OAuthException('You are not the owner of this security code', 1338318722);
			}
		}
	}


}