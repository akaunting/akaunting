<?php
namespace Aws\CloudWatchRUM;

use Aws\AwsClient;

/**
 * This client is used to interact with the **CloudWatch RUM** service.
 * @method \Aws\Result batchCreateRumMetricDefinitions(array $args = [])
 * @method \GuzzleHttp\Promise\Promise batchCreateRumMetricDefinitionsAsync(array $args = [])
 * @method \Aws\Result batchDeleteRumMetricDefinitions(array $args = [])
 * @method \GuzzleHttp\Promise\Promise batchDeleteRumMetricDefinitionsAsync(array $args = [])
 * @method \Aws\Result batchGetRumMetricDefinitions(array $args = [])
 * @method \GuzzleHttp\Promise\Promise batchGetRumMetricDefinitionsAsync(array $args = [])
 * @method \Aws\Result createAppMonitor(array $args = [])
 * @method \GuzzleHttp\Promise\Promise createAppMonitorAsync(array $args = [])
 * @method \Aws\Result deleteAppMonitor(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteAppMonitorAsync(array $args = [])
 * @method \Aws\Result deleteRumMetricsDestination(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteRumMetricsDestinationAsync(array $args = [])
 * @method \Aws\Result getAppMonitor(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getAppMonitorAsync(array $args = [])
 * @method \Aws\Result getAppMonitorData(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getAppMonitorDataAsync(array $args = [])
 * @method \Aws\Result listAppMonitors(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listAppMonitorsAsync(array $args = [])
 * @method \Aws\Result listRumMetricsDestinations(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listRumMetricsDestinationsAsync(array $args = [])
 * @method \Aws\Result listTagsForResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listTagsForResourceAsync(array $args = [])
 * @method \Aws\Result putRumEvents(array $args = [])
 * @method \GuzzleHttp\Promise\Promise putRumEventsAsync(array $args = [])
 * @method \Aws\Result putRumMetricsDestination(array $args = [])
 * @method \GuzzleHttp\Promise\Promise putRumMetricsDestinationAsync(array $args = [])
 * @method \Aws\Result tagResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise tagResourceAsync(array $args = [])
 * @method \Aws\Result untagResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise untagResourceAsync(array $args = [])
 * @method \Aws\Result updateAppMonitor(array $args = [])
 * @method \GuzzleHttp\Promise\Promise updateAppMonitorAsync(array $args = [])
 * @method \Aws\Result updateRumMetricDefinition(array $args = [])
 * @method \GuzzleHttp\Promise\Promise updateRumMetricDefinitionAsync(array $args = [])
 */
class CloudWatchRUMClient extends AwsClient {}
