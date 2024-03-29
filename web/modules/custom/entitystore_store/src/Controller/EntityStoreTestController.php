<?php

declare(strict_types = 1);

namespace Drupal\entitystore_store\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\entitystore_customer\Entity\Node\EntityStoreCustomer;
use Drupal\entitystore_partner\Entity\Node\EntityStorePartner;
use Drupal\entitystore_store\Entity\Node\EntityStoreStore;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Returns responses for EntityStore Store routes.
 */
final class EntityStoreTestController extends ControllerBase {

  /**
   * The controller constructor.
   */
  public function __construct(
     EntityTypeManagerInterface $entity_type_manager,
  ) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): self {
    return new self(
      $container->get('entity_type.manager'),
    );
  }

  /**
   * Builds the response.
   */
  public function __invoke(): array {

    $partner = $this->entityTypeManager->getStorage('node')->create(
    [
      'status' => 1,
      'title' => 'A Partner example',
      'type' => 'entitystore_partner',
    ]
    );
    $customer = $this->entityTypeManager->getStorage('node')->create(
    [
      'status' => 1,
      'title' => 'A customer example',
      'type' => 'entitystore_customer',
    ]
    );

    $store = $this->entityTypeManager->getStorage('node')->create(
    [
      'status' => 1,
      'title' => 'A Store example',
      'type' => 'entitystore_store',
    ]
    );

    $is_custom_bundle_classes = is_a($partner, EntityStorePartner::class) && is_a($customer, EntityStoreCustomer::class) && is_a($store, EntityStoreStore::class);

    $messages[] = $is_custom_bundle_classes ? $this->t('Success: Custom bundle Classes creation test OK</br>') : $this->t('Fail: Custom bundle Classes creation test FAILURE</br>');

    $partner_duplicate = $partner->createDuplicate();
    $partner_save_result = $partner->save();

    $partner_duplicate_save_result = $partner_duplicate->save();
    $messages[] = $partner_save_result && !$partner_duplicate_save_result ? $this->t('Success: Duplicate save protection OK</br>') : $this->t('Failure: Duplicate save protection FAILURE </br>');
    $result = '';
    foreach ($messages as $message) {
      $result .= $message;
    }

    $build['content'] = [
      '#type' => 'item',
      '#markup' => $result,
    ];

    return $build;
  }

}
