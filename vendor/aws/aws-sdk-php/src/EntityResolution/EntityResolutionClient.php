<?php
namespace Aws\EntityResolution;

use Aws\AwsClient;

/**
 * This client is used to interact with the **AWS EntityResolution** service.
 * @method \Aws\Result createMatchingWorkflow(array $args = [])
 * @method \GuzzleHttp\Promise\Promise createMatchingWorkflowAsync(array $args = [])
 * @method \Aws\Result createSchemaMapping(array $args = [])
 * @method \GuzzleHttp\Promise\Promise createSchemaMappingAsync(array $args = [])
 * @method \Aws\Result deleteMatchingWorkflow(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteMatchingWorkflowAsync(array $args = [])
 * @method \Aws\Result deleteSchemaMapping(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteSchemaMappingAsync(array $args = [])
 * @method \Aws\Result getMatchId(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getMatchIdAsync(array $args = [])
 * @method \Aws\Result getMatchingJob(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getMatchingJobAsync(array $args = [])
 * @method \Aws\Result getMatchingWorkflow(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getMatchingWorkflowAsync(array $args = [])
 * @method \Aws\Result getSchemaMapping(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getSchemaMappingAsync(array $args = [])
 * @method \Aws\Result listMatchingJobs(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listMatchingJobsAsync(array $args = [])
 * @method \Aws\Result listMatchingWorkflows(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listMatchingWorkflowsAsync(array $args = [])
 * @method \Aws\Result listSchemaMappings(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listSchemaMappingsAsync(array $args = [])
 * @method \Aws\Result listTagsForResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listTagsForResourceAsync(array $args = [])
 * @method \Aws\Result startMatchingJob(array $args = [])
 * @method \GuzzleHttp\Promise\Promise startMatchingJobAsync(array $args = [])
 * @method \Aws\Result tagResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise tagResourceAsync(array $args = [])
 * @method \Aws\Result untagResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise untagResourceAsync(array $args = [])
 * @method \Aws\Result updateMatchingWorkflow(array $args = [])
 * @method \GuzzleHttp\Promise\Promise updateMatchingWorkflowAsync(array $args = [])
 */
class EntityResolutionClient extends AwsClient {}
