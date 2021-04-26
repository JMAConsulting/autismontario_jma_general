<?php

namespace Drupal\jma_customizations\Plugin\GraphQL\Fields;

use Drupal\graphql\GraphQL\Execution\ResolveContext;
use Drupal\graphql\Plugin\GraphQL\Fields\FieldPluginBase;
use GraphQL\Type\Definition\ResolveInfo;

/**
 * A Search API Field.
 *
 * @GraphQLField(
 *   secure = true,
 *   parents = {"SearchAPIDocument"},
 *   id = "search_api_jma_field",
 *   deriver = "Drupal\jma_customizations\Plugin\GraphQL\Derivers\SearchAPIJmaFieldDeriver"
 * )
 */
class SearchAPIJmaField extends FieldPluginBase {

  /**
   * {@inheritdoc}
   */
  public function resolveValues($value, array $args, ResolveContext $context, ResolveInfo $info) {

    $derivative_id = $this->getDerivativeId();

    // Not all documents have values for all fields so we need to check.
    if (isset($value['item'][$derivative_id])) {

      $field = $value['item'][$derivative_id];

      $field_values = $field->getValues();
      $field_type = $field->getType();
      $value = NULL;
      //\Drupal::logger('graphql_search_api')->notice('field values %values and type %type field_type derivative id %derivativeid', ['%values' => json_encode($field_values), '%type' => json_encode($field_type), '%derivativeid'  => $derivative_id]);
      // Fulltext multivalue fields have a different format.
      if ($field_type == 'text') {
        // Create a new array with text values instead of objects.
        foreach ($field_values as $field_value) {
          $value[] = $field_value->getText();
        }
      }
      // For other types of fields we can just grab contents from the array.
      else {
        $value = $field_values;
      }
      if ($derivative_id == "custom_954") {
        \Drupal::logger('graphql_search_api')->notice('values %values', ['%values' => json_encode($value)]);
      }
      // Load the value in the response document.
      if (!is_null($value)) {
        // Checking if the value of this derivative is a list or single value so
        // we can parse accordingly.
        if (is_array($value)) {
          foreach ($value as $value_item) {
            yield $value_item;
          }
        }
        else {
          yield $value;
        }
      }
    }
  }

}
