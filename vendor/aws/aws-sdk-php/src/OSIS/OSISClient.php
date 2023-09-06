<?php
namespace Aws\OSIS;

use Aws\AwsClient;

/**
 * This client is used to interact with the **Amazon OpenSearch Ingestion** service.
 * @method \Aws\Result createPipeline(array $args = [])
 * @method \GuzzleHttp\Promise\Promise createPipelineAsync(array $args = [])
 * @method \Aws\Result deletePipeline(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deletePipelineAsync(array $args = [])
 * @method \Aws\Result getPipeline(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getPipelineAsync(array $args = [])
 * @method \Aws\Result getPipelineBlueprint(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getPipelineBlueprintAsync(array $args = [])
 * @method \Aws\Result getPipelineChangeProgress(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getPipelineChangeProgressAsync(array $args = [])
 * @method \Aws\Result listPipelineBlueprints(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listPipelineBlueprintsAsync(array $args = [])
 * @method \Aws\Result listPipelines(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listPipelinesAsync(array $args = [])
 * @method \Aws\Result listTagsForResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listTagsForResourceAsync(array $args = [])
 * @method \Aws\Result startPipeline(array $args = [])
 * @method \GuzzleHttp\Promise\Promise startPipelineAsync(array $args = [])
 * @method \Aws\Result stopPipeline(array $args = [])
 * @method \GuzzleHttp\Promise\Promise stopPipelineAsync(array $args = [])
 * @method \Aws\Result tagResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise tagResourceAsync(array $args = [])
 * @method \Aws\Result untagResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise untagResourceAsync(array $args = [])
 * @method \Aws\Result updatePipeline(array $args = [])
 * @method \GuzzleHttp\Promise\Promise updatePipelineAsync(array $args = [])
 * @method \Aws\Result validatePipeline(array $args = [])
 * @method \GuzzleHttp\Promise\Promise validatePipelineAsync(array $args = [])
 */
class OSISClient extends AwsClient {}
