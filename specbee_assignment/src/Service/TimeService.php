<?php

namespace Drupal\specbee_assignment\Service;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Security\TrustedCallbackInterface;

/**
 * Service to get current time.
 *
 * @package Drupal\specbee_assignment\Service
 */
class TimeService implements TrustedCallbackInterface {

  /**
   * A configuration object.
   *
   * @var \Drupal\Core\Config\ImmutableConfig
   */
  protected $config;

  /**
   * CustomService constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configfactory
   *   The configuration factory.
   */
  public function __construct(ConfigFactoryInterface $configfactory) {
    $this->config = $configfactory;
  }

  /**
   * Get current date and Time.
   */
  public function getCurrentDateTime() {
    $timezone = $this->config->get('specbee_assignment.settings')->get('timezone');
    $current_timestamp = (new DrupalDateTime())->getTimestamp();
    $dateTime = DrupalDateTime::createFromTimestamp($current_timestamp, $timezone);

    return [
      '#markup' => $dateTime->format('jS M Y - h:i A'),
      '#cache' => [
        'max-age' => 0,
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public static function trustedCallbacks() {
    return [
      'getCurrentDateTime',
    ];
  }

}
