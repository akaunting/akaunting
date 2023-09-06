<?php
namespace Aws\KendraRanking;

use Aws\AwsClient;

/**
 * This client is used to interact with the **Amazon Kendra Intelligent Ranking** service.
 * @method \Aws\Result createRescoreExecutionPlan(array $args = [])
 * @method \GuzzleHttp\Promise\Promise createRescoreExecutionPlanAsync(array $args = [])
 * @method \Aws\Result deleteRescoreExecutionPlan(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteRescoreExecutionPlanAsync(array $args = [])
 * @method \Aws\Result describeRescoreExecutionPlan(array $args = [])
 * @method \GuzzleHttp\Promise\Promise describeRescoreExecutionPlanAsync(array $args = [])
 * @method \Aws\Result listRescoreExecutionPlans(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listRescoreExecutionPlansAsync(array $args = [])
 * @method \Aws\Result listTagsForResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listTagsForResourceAsync(array $args = [])
 * @method \Aws\Result rescore(array $args = [])
 * @method \GuzzleHttp\Promise\Promise rescoreAsync(array $args = [])
 * @method \Aws\Result tagResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise tagResourceAsync(array $args = [])
 * @method \Aws\Result untagResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise untagResourceAsync(array $args = [])
 * @method \Aws\Result updateRescoreExecutionPlan(array $args = [])
 * @method \GuzzleHttp\Promise\Promise updateRescoreExecutionPlanAsync(array $args = [])
 */
class KendraRankingClient extends AwsClient {}
