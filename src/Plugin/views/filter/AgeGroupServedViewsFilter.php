<?php

namespace Drupal\jma_customizations\Plugin\views\filter;

use Drupal\jma_customizations\Plugin\views\filter\SelectViewsFilter;
use Drupal\views\Plugin\views\display\DisplayPluginBase;
use Drupal\views\ViewExecutable;
use Drupal\views\Views;

/**
 * Filters by lang of entity.
 *
 * @ingroup views_filter_handlers
 *
 * @ViewsFilter("age_group_served_views_filter")
 */
class AgeGroupServedViewsFilter extends SelectViewsFilter {

  /**
   * {@inheritdoc}
   */
  public function init(ViewExecutable $view, DisplayPluginBase $display, array &$options = NULL) {
    parent::init($view, $display, $options);
    $this->valueTitle = t('Age Group Served');
    $this->field = 'custom_898';
  }

  /**
   * Helper function that generates the options.
   *
   * @return array
   *   An array of states and their ids.
   */
  public function generateOptions() {
    return \CRM_Core_OptionGroup::values('age_groups_served_20200226231233');
  }

}
