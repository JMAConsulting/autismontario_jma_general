<?php

namespace Drupal\jma_customizations\Plugin\views\filter;

use Drupal\views\Plugin\views\display\DisplayPluginBase;
use Drupal\views\Plugin\views\filter\ManyToOne;
use Drupal\views\Plugin\views\filter\InOperator;
use Drupal\views\ViewExecutable;
use Drupal\views\Views;

/**
 * Filters by lang of entity.
 *
 * @ingroup views_filter_handlers
 *
 * @ViewsFilter("select_views_filter")
 */
class SelectViewsFilter extends InOperator {

  /**
   * The current display.
   *
   * @var string
   *   The current display of the view.
   */
  protected $currentDisplay;

  protected $valueFormType = 'select';

  public $field;

  /**
   * {@inheritdoc}
   */
  public function init(ViewExecutable $view, DisplayPluginBase $display, array &$options = NULL) {
    parent::init($view, $display, $options);
    $this->valueTitle = t('Language filter');
    $this->definition['options callback'] = [$this, 'generateOptions'];
    $this->currentDisplay = $view->current_display;
    // Load Civi service so we can use its query lib.
    \Drupal::service('civicrm')->initialize();
  }

  /**
   * Helper function that builds the query.
   */
  public function query() {
    // Limit to just the searchAPIQuery class of queries
    if ($this->query instanceof \Drupal\search_api\Plugin\views\query\SearchApiQuery) {
      // If we have selected values process them
      if (!empty($this->value)) {
        // Use the array values as the labels have been indexed not the option value values
        $values = array_values($this->value);
        // Create a condition group where each selected option will be added in an OR
        $conditionGroup = $this->query->createConditionGroup('OR');
        foreach ($values as $value) {
          $indexedValue = $this->generateOptions()[$value];
          if (!empty($indexedValue)) {
            // Add each value as a contains condition to the OR condition group
            $conditionGroup->addCondition($this->field, $indexedValue);
          }
        }
        // Now add the or condition group back to the original condition group which is in AND
        $this->query->addConditionGroup($conditionGroup);
      }
    }
    if (!($this->query instanceof Sql)) {
      return;
    }

    if (empty($this->value)) {
      return;
    }
  }

}
