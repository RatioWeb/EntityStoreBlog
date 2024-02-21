<?php

declare(strict_types = 1);

namespace Drupal\entitystore_partner\Entity\Node;

use Drupal\node\Entity\Node;

/**
 * A bundle class for node entities.
 */
final class EntityStorePartner extends Node {

  /**
   * Set during createDuplicate().
   *
   * @var bool
   */
  protected bool $isDuplicate = FALSE;

  /**
   * {@inheritDoc}
   */
  public function createDuplicate() {
    $duplicate = parent::createDuplicate();
    $duplicate->setUnpublished();
    $duplicate->setDuplicate(TRUE);
    return $duplicate;
  }

  /**
   * {@inheritDoc}
   */
  public function save() {
    if (!$this->getIsDuplicate()) {
      return parent::save();
    }
    else {
      return FALSE;
    }
  }

  /**
   * Checks whether the entity is a duplicate.
   */
  public function getIsDuplicate():bool {
    return $this->isDuplicate;
  }

  /**
   * Flags as duplicate.
   *
   * @param bool $is_duplicate
   *   Is it to be a duplicate?
   */
  protected function setDuplicate(bool $is_duplicate = TRUE) {
    $this->isDuplicate = $is_duplicate;
  }

}
