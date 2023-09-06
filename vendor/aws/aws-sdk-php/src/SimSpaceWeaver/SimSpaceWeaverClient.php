<?php
namespace Aws\SimSpaceWeaver;

use Aws\AwsClient;

/**
 * This client is used to interact with the **AWS SimSpace Weaver** service.
 * @method \Aws\Result createSnapshot(array $args = [])
 * @method \GuzzleHttp\Promise\Promise createSnapshotAsync(array $args = [])
 * @method \Aws\Result deleteApp(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteAppAsync(array $args = [])
 * @method \Aws\Result deleteSimulation(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteSimulationAsync(array $args = [])
 * @method \Aws\Result describeApp(array $args = [])
 * @method \GuzzleHttp\Promise\Promise describeAppAsync(array $args = [])
 * @method \Aws\Result describeSimulation(array $args = [])
 * @method \GuzzleHttp\Promise\Promise describeSimulationAsync(array $args = [])
 * @method \Aws\Result listApps(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listAppsAsync(array $args = [])
 * @method \Aws\Result listSimulations(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listSimulationsAsync(array $args = [])
 * @method \Aws\Result listTagsForResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listTagsForResourceAsync(array $args = [])
 * @method \Aws\Result startApp(array $args = [])
 * @method \GuzzleHttp\Promise\Promise startAppAsync(array $args = [])
 * @method \Aws\Result startClock(array $args = [])
 * @method \GuzzleHttp\Promise\Promise startClockAsync(array $args = [])
 * @method \Aws\Result startSimulation(array $args = [])
 * @method \GuzzleHttp\Promise\Promise startSimulationAsync(array $args = [])
 * @method \Aws\Result stopApp(array $args = [])
 * @method \GuzzleHttp\Promise\Promise stopAppAsync(array $args = [])
 * @method \Aws\Result stopClock(array $args = [])
 * @method \GuzzleHttp\Promise\Promise stopClockAsync(array $args = [])
 * @method \Aws\Result stopSimulation(array $args = [])
 * @method \GuzzleHttp\Promise\Promise stopSimulationAsync(array $args = [])
 * @method \Aws\Result tagResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise tagResourceAsync(array $args = [])
 * @method \Aws\Result untagResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise untagResourceAsync(array $args = [])
 */
class SimSpaceWeaverClient extends AwsClient {}
