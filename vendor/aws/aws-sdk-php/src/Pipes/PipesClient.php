<?php
namespace Aws\Pipes;

use Aws\AwsClient;

/**
 * This client is used to interact with the **Amazon EventBridge Pipes** service.
 * @method \Aws\Result createPipe(array $args = [])
 * @method \GuzzleHttp\Promise\Promise createPipeAsync(array $args = [])
 * @method \Aws\Result deletePipe(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deletePipeAsync(array $args = [])
 * @method \Aws\Result describePipe(array $args = [])
 * @method \GuzzleHttp\Promise\Promise describePipeAsync(array $args = [])
 * @method \Aws\Result listPipes(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listPipesAsync(array $args = [])
 * @method \Aws\Result listTagsForResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listTagsForResourceAsync(array $args = [])
 * @method \Aws\Result startPipe(array $args = [])
 * @method \GuzzleHttp\Promise\Promise startPipeAsync(array $args = [])
 * @method \Aws\Result stopPipe(array $args = [])
 * @method \GuzzleHttp\Promise\Promise stopPipeAsync(array $args = [])
 * @method \Aws\Result tagResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise tagResourceAsync(array $args = [])
 * @method \Aws\Result untagResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise untagResourceAsync(array $args = [])
 * @method \Aws\Result updatePipe(array $args = [])
 * @method \GuzzleHttp\Promise\Promise updatePipeAsync(array $args = [])
 */
class PipesClient extends AwsClient {}
