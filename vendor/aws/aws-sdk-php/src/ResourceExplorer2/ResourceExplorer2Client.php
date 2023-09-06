<?php
namespace Aws\ResourceExplorer2;

use Aws\AwsClient;

/**
 * This client is used to interact with the **AWS Resource Explorer** service.
 * @method \Aws\Result associateDefaultView(array $args = [])
 * @method \GuzzleHttp\Promise\Promise associateDefaultViewAsync(array $args = [])
 * @method \Aws\Result batchGetView(array $args = [])
 * @method \GuzzleHttp\Promise\Promise batchGetViewAsync(array $args = [])
 * @method \Aws\Result createIndex(array $args = [])
 * @method \GuzzleHttp\Promise\Promise createIndexAsync(array $args = [])
 * @method \Aws\Result createView(array $args = [])
 * @method \GuzzleHttp\Promise\Promise createViewAsync(array $args = [])
 * @method \Aws\Result deleteIndex(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteIndexAsync(array $args = [])
 * @method \Aws\Result deleteView(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteViewAsync(array $args = [])
 * @method \Aws\Result disassociateDefaultView(array $args = [])
 * @method \GuzzleHttp\Promise\Promise disassociateDefaultViewAsync(array $args = [])
 * @method \Aws\Result getDefaultView(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getDefaultViewAsync(array $args = [])
 * @method \Aws\Result getIndex(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getIndexAsync(array $args = [])
 * @method \Aws\Result getView(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getViewAsync(array $args = [])
 * @method \Aws\Result listIndexes(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listIndexesAsync(array $args = [])
 * @method \Aws\Result listSupportedResourceTypes(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listSupportedResourceTypesAsync(array $args = [])
 * @method \Aws\Result listTagsForResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listTagsForResourceAsync(array $args = [])
 * @method \Aws\Result listViews(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listViewsAsync(array $args = [])
 * @method \Aws\Result search(array $args = [])
 * @method \GuzzleHttp\Promise\Promise searchAsync(array $args = [])
 * @method \Aws\Result tagResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise tagResourceAsync(array $args = [])
 * @method \Aws\Result untagResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise untagResourceAsync(array $args = [])
 * @method \Aws\Result updateIndexType(array $args = [])
 * @method \GuzzleHttp\Promise\Promise updateIndexTypeAsync(array $args = [])
 * @method \Aws\Result updateView(array $args = [])
 * @method \GuzzleHttp\Promise\Promise updateViewAsync(array $args = [])
 */
class ResourceExplorer2Client extends AwsClient {}
