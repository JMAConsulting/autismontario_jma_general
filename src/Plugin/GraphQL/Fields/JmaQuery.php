<?php

namespace Drupal\jma_customizations\Plugin\GraphQL\Fields;

use Drupal\graphql_core\Plugin\GraphQL\Fields\EntityQuery\EntityQuery;

/**
 * @GraphQLField(
 *   id = "jma_query",
 *   secure = true,
 *   type = "EntityQueryResult",
 *   arguments = {
 *     "filter" = "EntityQueryFilterInput",
 *     "sort" = "[EntityQuerySortInput]",
 *     "offset" = {
 *       "type" = "Int",
 *       "default" = 0
 *     },
 *     "limit" = {
 *       "type" = "Int",
 *       "default" = 25
 *     },
 *     "revisions" = {
 *       "type" = "EntityQueryRevisionMode",
 *       "default" = "default"
 *     }
 *   },
 *   deriver = "Drupal\jma_customizations\Plugin\Deriver\Fields\JmaQueryDeriver"
 * )
 *
 * This field is marked as not secure because it does not enforce entity field
 * access over a chain of filters. For example node.uid.pass could be used as
 * filter input which would disclose information about Drupal's password hashes.
 */
class JmaQuery extends EntityQuery {

}
