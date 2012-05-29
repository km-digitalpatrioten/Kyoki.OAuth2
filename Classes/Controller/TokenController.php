<?php
namespace Kyoki\OAuth2\Controller;

/*                                                                        *
 * This script belongs to the FLOW3 package "Kyoki.OAuth2".               *
 *                                                                        *
 *                                                                        */

use TYPO3\FLOW3\Annotations as FLOW3;
use Kyoki\OAuth2\Domain\Model\OAuthCode;
use Kyoki\OAuth2\Domain\Model\OAuthToken;
use Kyoki\OAuth2\Exception\OAuthException;
use Kyoki\OAuth2\Controller\OAuthAbstractController;

/**
 * Token controller for the Kyoki.OAuth2 package
 *
 * @FLOW3\Scope("singleton")
 */
class TokenController extends OAuthAbstractController
{
    /**
     * @var \Kyoki\OAuth2\Domain\Repository\OAuthTokenRepository
     * @FLOW3\Inject
     */
    protected $oauthTokenRepository;


    /**
     * Token endpoint
     *
     * @param \Kyoki\OAuth2\Domain\Model\OAuthCode $oauthCode
     * @param string $grant_type
     * @param string $refresh_token
     */
    public function tokenAction($grant_type, OAuthCode $oauthCode = NULL, $refresh_token = ''  ) {
        if ($grant_type == 'refresh_token') {
            $token = $this->oauthTokenRepository->findByRefreshToken($refresh_token);
            if ($token) {
                $this->oauthTokenRepository->remove($token);

            }
        }
        $token = new OAuthToken($oauthCode,3600,'Bearer');
        $this->oauthTokenRepository->add($token);
        return json_encode(array(
            'access_token'  => $token->getAccessToken(),
            'token_type'    => $token->getTokenType(),
            'expires_in'    => $token->getExpiresIn(),
            'refresh_token' => $token->getRefreshToken()
        ));
    }

}