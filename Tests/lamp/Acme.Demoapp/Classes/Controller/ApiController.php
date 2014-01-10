<?php
namespace Acme\Demoapp\Controller;
use TYPO3\Flow\Annotations as Flow;

/*                                                                        *
* This script belongs to the FLOW3 package "F2.TuitLawyer".              *
*                                                                        *
*                                                                        */

/**
 * Standard controller for the F2.TuitLawyer package
 *
 * @Flow\Scope("singleton")
 */
class ApiController extends \TYPO3\Flow\Mvc\Controller\ActionController {
    public function resourceAction(){
        $this->response->setContent(json_encode(array('something' => 'secured')));
	    $this->response->setHeader('Content-Type', 'application/json');
    }
}

?>