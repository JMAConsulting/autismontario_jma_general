<?php

namespace Drupal\jma_customizations\Plugin\views\filter;

use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Plugin\views\filter\FilterPluginBase;
use Drupal\views\Plugin\views\query\Sql;
use Drupal\geolocation\GeocoderManager;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Render\BubbleableMetadata;
use Drupal\geolocation\BoundaryTrait;
use Drupal\Component\Utility\NestedArray;

/**
 * Filter handler for search keywords.
 *
 * @ingroup views_filter_handlers
 *
 * @ViewsFilter("geolocation_proximity_views_filter")
 */
class GeolocationProximityViewsFilter extends FilterPluginBase implements ContainerFactoryPluginInterface {

  use BoundaryTrait;

  /**
   * {@inheritdoc}
   */
  public $no_operator = TRUE;

  /**
   * {@inheritdoc}
   */
  protected $alwaysMultiple = TRUE;

  /**
   * The GeocoderManager object.
   *
   * @var \Drupal\geolocation\GeocoderManager
   */
  protected $geocoderManager;

  /**
   * Constructs a Handler object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\geolocation\GeocoderManager $geocoder_manager
   *   The Geocoder manager.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, GeocoderManager $geocoder_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->geocoderManager = $geocoder_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('plugin.manager.geolocation.geocoder')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function adminSummary() {
    return $this->t("exposed Proximity Filter");
  }

  /**
   * {@inheritdoc}
   */
  public function buildExposedForm(&$form, FormStateInterface $form_state) {
    parent::buildExposedForm($form, $form_state);

    $identifier = $this->options['expose']['identifier'];

    $form[$identifier] = [
      'distance' => [
        '#title' => $this->t('Distance (in km)'),
        '#type' => 'textfield',
        '#default_value' => 0,
        '#weight' => 0,
        '#size' => 30,
      ],
      'current_location' => [
        '#title' => $this->t('From my current Location'),
        '#type' => 'checkbox',
        '#default_value' => 0,
        '#weight' => 1,
      ],
      'street_address' => [
        '#title' => $this->t('Street Address'),
        '#type' => 'textfield',
        '#default_value' => '',
        '#weight' => 2,
        '#size' => 50,
      ],
      'city' => [
        '#title' => $this->t('City'),
        '#type' => 'textfield',
        '#default_value' => '',
        '#weight' => 3,
        '#size' => 10,
      ],
      'postal_code' => [
        '#title' => $this->t('Postal Code'),
        '#type' => 'textfield',
        '#default_value' => '',
        '#weight' => 4,
        '#size' => 5,
      ],
      'center_lat' => [
        '#type' => 'hidden',
        '#value' => NULL,
      ],
      'center_long' => [
        '#type' => 'hidden',
        '#value' => NULL,
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function acceptExposedInput($input) {

    $return_value = parent::acceptExposedInput($input);

    //@TODO
    return $return_value;
  }

  /**
   * {@inheritdoc}
   */
  protected function valueForm(&$form, FormStateInterface $form_state) {

    parent::valueForm($form, $form_state);

    $form['value']['#tree'] = TRUE;
    $value_element = &$form['value'];
  }

  /**
   * {@inheritdoc}
   */
  public function query() {
    \Drupal::logger('geolocation')->notice(serialize($this->value));
    if (!($this->query instanceof Sql)) {
      return;
    }

    if (empty($this->value)) {
      return;
    }
    /*
    $this->query->addWhereExpression(
      $this->options['group'],
      self::getBoundaryQueryFragment($this->ensureMyTable(), $this->realField)
    );
    */
  }

}
