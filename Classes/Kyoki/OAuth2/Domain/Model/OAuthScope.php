<?php
namespace Kyoki\OAuth2\Domain\Model;
/*                                                                        *
 * This script belongs to the Kyoki.OAuth2 package.                        *
 * @author Fernando Arconada <fernando.arconada@gmail.com>                *
 *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 *                                                                        */
use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;

/**
 * An OAuth consumer
 *
 * @Flow\Entity
 */
class OAuthScope {
	/**
	 * @Flow\Identity
	 * @ORM\Id
	 * @Flow\Validate(type="Text")
	 * @var string
	 */
	protected $id;

	/**
	 * @var string
	 */
	protected $description;

	public function __construct($id, $description = '') {
		$this->id = $id;
		$this->setDescription($description);
	}

	/**
	 * @param string $description
	 */
	public function setDescription($description) {
		$this->description = $description;
	}

	/**
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
	}


	/**
	 * @return string
	 */
	public function getId() {
		return $this->id;
	}

}
