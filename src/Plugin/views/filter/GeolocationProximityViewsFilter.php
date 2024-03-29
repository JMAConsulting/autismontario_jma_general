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
  protected function valueForm(&$form, FormStateInterface $form_state) {

    parent::valueForm($form, $form_state);

    $form['value']['#tree'] = TRUE;
    $value_element = &$form['value'];

    $value_element += [
      'distance' => [
        '#type' => 'select',
        '#title' => $this->t('Distance (in km)'),
        '#default_value' => !empty($this->value['distance']) ? $this->value['distance'] : 0,
        '#options' => [
          5 => '5',
          10 => '10',
          25 => '25',
          50 => '50',
          100 => '100',
          250 => '250',
        ],
        '#weight' => 10,
      ],
      'current_location' => [
        '#type' => 'checkbox',
        '#title' => $this->t('From my current location'),
        '#default_value' => !empty($this->value['current_location']) ? $this->value['current_location'] : 0,
        '#weight' => 20,
      ],
      'street_address' => [
        '#type' => 'textfield',
        '#title' => $this->t('Street Address'),
        '#default_value' => !empty($this->value['street_address']) ? $this->value['street_address'] : '',
        '#weight' => 30,
      ],
      'city' => [
        '#type' => 'textfield',
        '#title' => $this->t('City'),
        '#default_value' => !empty($this->value['city']) ? $this->value['city'] : '',
        '#weight' => 40,
      ],
      'postal_code' => [
        '#type' => 'textfield',
        '#title' => $this->t('Postal Code'),
        '#default_value' => !empty($this->value['postal_code']) ? $this->value['postal_code'] : '',
        '#weight' => 40,
      ],
      'center_lat' => [
        '#type' => 'textfield',
        '#default_value' => NULL,
      ],
      'center_long' => [
        '#type' => 'textfield',
        '#default_value' => NULL,
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function query() {
    if ($this->query instanceof \Drupal\search_api\Plugin\views\query\SearchApiQuery) {
      $location_option_params = [
        'field' => $this->realField,
        'radius' => $this->value['distance'],
      ];
      if (!empty($this->value['center_lat'])) {
        $location_option_params['lat'] = $this->value['center_lat'];
        $location_option_params['lon'] = $this->value['center_long'];
      }
      else {
        $address = [];
        foreach (['street_address', 'city', 'postal_code'] as $key) {
          if (!empty($this->value[$key])) {
            $address[] = $this->value[$key];
          }
        }
        // Limit to Ontario,Canada only when other address information is supplied
        if (!empty($address)) {
          $address[] = 'ON';
          $address[] = 'CA';
        }
        $address = implode(',', $address);
        if (!empty($address)) {
          $result = $this->geocoder($address);
          if (!empty($result['results'][0]['geometry']['location'])) {
            $location_option_params['lat'] = $result['results'][0]['geometry']['location']['lat'];
            $location_option_params['lon'] = $result['results'][0]['geometry']['location']['lng'];
          }
        }
      }
      if (!empty($location_option_params['lat']) && !empty($location_option_params['lon'])) {
        $this->query->setOption('search_api_location', [$location_option_params]);
      }
    }
    if (!($this->query instanceof Sql)) {
      return;
    }

    if (empty($this->value)) {
      return;
    }
  }

  public function geocoder($address) {
    $config = \Drupal::config('geolocation_google_maps.settings');

    $request_url = \Drupal\geolocation_google_maps\GoogleMapsProviderBase::$googleMapsApiUrlBase
      . '/maps/api/geocode/json?address='
      . urlencode($address)
      . '&key=' . $config->get('google_map_api_key');

    if (!empty($config->get('google_map_custom_url_parameters')['language'])) {
      $request_url .= '&language=' . $config->get('google_map_custom_url_parameters')['language'];
    }

    try {
      $result = \Drupal\Component\Serialization\Json::decode(\Drupal::httpClient()->request('GET', $request_url)->getBody());
    }
    catch (\GuzzleHttp\Exception\RequestException $e) {
      watchdog_exception('geolocation', $e);
      return FALSE;
    }

    return $result;
  }

}
