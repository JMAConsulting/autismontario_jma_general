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
 * @ViewsFilter("lang_views_filter")
 */
class LangViewsFilter extends SelectViewsFilter {

  /**
   * {@inheritdoc}
   */
  public function init(ViewExecutable $view, DisplayPluginBase $display, array &$options = NULL) {
    parent::init($view, $display, $options);
    $this->valueTitle = t('Language filter');
    $this->field = 'custom_899';
  }

  /**
   * Helper function that generates the options.
   *
   * @return array
   *   An array of states and their ids.
   */
  public function generateOptions() {
    $languages = array_merge(
      \CRM_Core_OptionGroup::values('language_20180621140924'),
      \CRM_Core_OptionGroup::values('language_of_event_20181119205706')
    );
    return $languages;
  }

}
