<?php
namespace Acme\Demoapp\Command;
/**
 * Created by JetBrains PhpStorm.
 * User: fernando
 * Date: 28/06/12
 * Time: 10:47
 * To change this template use File | Settings | File Templates.
 */


use TYPO3\Flow\Annotations as Flow;
use Kyoki\OAuth2\Domain\Model\OAuthClient as OAuthClient;
use Kyoki\OAuth2\Domain\Model\OAuthScope as OAuthScope;
use Acme\Demoapp\Domain\Model\User as User;
/**
 *
 * @Flow\Scope("singleton")
 */
class InitCommandController extends \TYPO3\Flow\Cli\CommandController {
	const USERNAME = 'username';
	const PASSWORD = 'password';
	const ROLE = 'User';
	const CLIENT_ID = 'AAAAAAAAAAAAAA';
	const CLIENT_SECRET = 'SSSSSSSSSSSSSSS';
	const SCOPE = 'myscope';
	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Security\AccountRepository
	 */
	protected $accountRepository;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Security\AccountFactory
	 */
	protected $accountFactory;

	/**
	 * @Flow\Inject
	 * @var \Kyoki\OAuth2\Domain\Repository\OAuthClientRepository
	 */
	protected $clientRepository;

	/**
	 * @Flow\Inject
	 * @var \Kyoki\OAuth2\Domain\Repository\OAuthScopeRepository
	 */
	protected $scopeRepository;


	public function createAccountCommand() {
		if(!$this->accountRepository->findByAccountIdentifierAndAuthenticationProviderName(self::USERNAME, 'DefaultProvider')) {
			$account = $this->accountFactory->createAccountWithPassword(self::USERNAME, self::PASSWORD, array(self::ROLE));
			$this->accountRepository->add($account);
			$this->outputLine('account created, username: "%s", password: "%s"', array(self::USERNAME, self::PASSWORD));
		}
	}

	public function createClientapiCommand() {
		if(!$this->clientRepository->findByIdentifier(self::CLIENT_ID)){
 			$account = $this->accountFactory->createAccountWithPassword(self::USERNAME.'-api-owner', self::PASSWORD, array(self::ROLE));
            $this->accountRepository->add($account);
			$client = new OAuthClient($account,'Demo API Client','http://');
			$client->setClientId(self::CLIENT_ID);
			$client->setSecret(self::CLIENT_SECRET);
			$this->clientRepository->add($client);
			$this->outputLine('API client created, client_id: "%s", client_secret: "%s"', array(self::CLIENT_ID, self::CLIENT_SECRET));
		}
	}

	public function createScopeCommand() {
		if(!$this->scopeRepository->findByIdentifier(self::SCOPE)){
			$scope = new OAuthScope(self::SCOPE,'Grant access to a demo resource');
			$this->scopeRepository->add($scope);
			$this->outputLine('scope created, id: "%s"', array(self::SCOPE));
		}	
	}
}
