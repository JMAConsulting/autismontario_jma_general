<?php

namespace Drupal\jma_customizations\Plugin\views\filter;

use Drupal\views\Plugin\views\filter\BooleanOperator;
use Drupal\views\Plugin\views\display\DisplayPluginBase;
use Drupal\views\ViewExecutable;
use Drupal\views\Views;

/**
 * Filters by lang of entity.
 *
 * @ingroup views_filter_handlers
 *
 * @ViewsFilter("accepting_client_views_filter")
 */
class AcceptingClientViewsFilter extends BooleanOperator {

  public function query() {
    if ($this->query instanceof \Drupal\search_api\Plugin\views\query\SearchApiQuery) {
      $operator = $this->value ? '=' : '<>';
      $this->query->addCondition($this->realField, 'Accepting new clients', $operator);
      $this->query->addCondition('contact_sub_type', 'service_provider', '=');
    }
  }

}
