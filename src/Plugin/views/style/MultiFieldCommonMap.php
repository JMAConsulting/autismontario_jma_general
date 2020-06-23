<?php
namespace Drupal\jma_customizations\Plugin\views\style;

use Drupal\geolocation\Plugin\views\style\CommonMap;
use Drupal\views\Plugin\views\style\StylePluginBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\image\Entity\ImageStyle;
use Drupal\views\ResultRow;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Component\Utility\NestedArray;
use Drupal\Component\Render\PlainTextOutput;


/**
 * Allow to display several field items on a common map.
 *
 * @ingroup views_style_plugins
 *
 * @ViewsStyle(
 *   id = "multi_field_maps_common",
 *   title = @Translation("MultiFIeld Geolocation CommonMap"),
 *   help = @Translation("Display geolocations on a common map."),
 *   theme = "views_view_list",
 *   display_types = {"normal"},
 * )
 */
class MultiFieldCommonMap extends CommonMap {

  /**
   * Render array from views result row.
   *
   * @param \Drupal\views\ResultRow $row
   *   Result row.
   *
   * @return array
   *   List of location render elements.
   */
  protected function getLocationsFromRow(ResultRow $row) {
    $locations = [];

    if (!empty($this->titleField)) {
      if (!empty($this->rendered_fields[$row->index][$this->titleField])) {
        $title_build = $this->rendered_fields[$row->index][$this->titleField];
      }
      elseif (!empty($this->view->field[$this->titleField])) {
        $title_build = $this->view->field[$this->titleField]->render($row);
      }
    }

    if (!empty($this->labelField)) {
      if (!empty($this->rendered_fields[$row->index][$this->labelField])) {
        $label_build = $this->rendered_fields[$row->index][$this->labelField];
      }
      elseif (!empty($this->view->field[$this->labelField])) {
        $label_build = $this->view->field[$this->labelField]->render($row);
      }
      else {
        $label_build = '';
      }
      $label_build = PlainTextOutput::renderFromHtml($label_build);
    }

    $icon_url = NULL;
    if (!empty($this->iconField)) {
      /** @var \Drupal\views\Plugin\views\field\Field $icon_field_handler */
      $icon_field_handler = $this->view->field[$this->iconField];
      if (!empty($icon_field_handler)) {
        $image_items = $icon_field_handler->getItems($row);
        if (!empty($image_items[0]['rendered']['#item']->entity)) {
          $file_uri = $image_items[0]['rendered']['#item']->entity->getFileUri();

          $style = NULL;
          if (!empty($image_items[0]['rendered']['#image_style'])) {
            /** @var \Drupal\image\Entity\ImageStyle $style */
            $style = ImageStyle::load($image_items[0]['rendered']['#image_style']);
          }

          if (!empty($style)) {
            $icon_url = file_url_transform_relative($style->buildUrl($file_uri));
          }
          else {
            $icon_url = file_url_transform_relative(file_create_url($file_uri));
          }
        }
      }
    }
    elseif (!empty($this->options['marker_icon_path'])) {
      $icon_token_uri = $this->viewsTokenReplace($this->options['marker_icon_path'], $this->rowTokens[$row->index]);
      $icon_token_uri = preg_replace('/\s+/', '', $icon_token_uri);
      $icon_url = file_url_transform_relative(file_create_url($icon_token_uri));
    }

    $data_provider = $this->dataProviderManager->createInstance($this->options['data_provider_id'], $this->options['data_provider_settings']);

    foreach ([$this->options['geolocation_field'], 'field_geolocation'] as $geolocation_field) {
      foreach ($data_provider->getPositionsFromViewsRow($row, $this->view->field[$geolocation_field]) as $position) {
        $location = [
          '#type' => 'geolocation_map_location',
          'content' => $this->view->rowPlugin->render($row),
          '#title' => empty($title_build) ? '' : $title_build,
          '#label' => empty($label_build) ? '' : $label_build,
          '#position' => $position,
          '#weight' => $row->index,
          '#attributes' => ['data-views-row-index' => $row->index],
        ];

        if (!empty($icon_url)) {
          $location['#icon'] = $icon_url;
        }

        if (!empty($location_id)) {
          $location['#id'] = $location_id;
        }

        if ($this->options['marker_row_number']) {
          $markerOffset = $this->view->pager->getCurrentPage() * $this->view->pager->getItemsPerPage();
          $marker_row_number = (int) $markerOffset + (int) $row->index + 1;
          if (empty($location['#label'])) {
            $location['#label'] = $marker_row_number;
          }
          else {
            $location['#label'] = $location['#label'] . ': ' . $location['#label'];
          }
        }

        $locations[] = $location;
      }
    }

    return $locations;
  }

}
