<?php

namespace Drupal\specbee_assignment\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Provides a 'Time' Block.
 *
 * @Block(
 *   id = "time_block",
 *   admin_label = @Translation("Time block"),
 * )
 */
class TimeBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * A configuration object.
   *
   * @var \Drupal\Core\Config\ImmutableConfig
   */
  protected $configs;

  /**
   * Creates the task block instance.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation defination.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configfactory
   *   The config factory.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ConfigFactoryInterface $configfactory) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->configs = $configfactory->get('specbee_assignment.settings');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
          $configuration,
          $plugin_id,
          $plugin_definition,
          $container->get('config.factory'),
      );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $data = [];
    $data['city'] = $this->configs->get('city');
    $data['country'] = $this->configs->get('country');
    $data['date_time'] = [
      '#lazy_builder' => ['specbee_assignment.time_services:getCurrentDateTime',
          [],
      ],
      '#create_placeholder' => TRUE,
    ];

    return [
      '#theme' => 'specbee_assignment',
      '#data' => $data,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheTags() {
    return Cache::mergeTags(parent::getCacheTags(), [
      'config:specbee_assignment.settings',
    ]);
  }

}
