<?php
namespace Aws\SsmSap;

use Aws\AwsClient;

/**
 * This client is used to interact with the **AWS Systems Manager for SAP** service.
 * @method \Aws\Result deleteResourcePermission(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteResourcePermissionAsync(array $args = [])
 * @method \Aws\Result deregisterApplication(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deregisterApplicationAsync(array $args = [])
 * @method \Aws\Result getApplication(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getApplicationAsync(array $args = [])
 * @method \Aws\Result getComponent(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getComponentAsync(array $args = [])
 * @method \Aws\Result getDatabase(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getDatabaseAsync(array $args = [])
 * @method \Aws\Result getOperation(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getOperationAsync(array $args = [])
 * @method \Aws\Result getResourcePermission(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getResourcePermissionAsync(array $args = [])
 * @method \Aws\Result listApplications(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listApplicationsAsync(array $args = [])
 * @method \Aws\Result listComponents(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listComponentsAsync(array $args = [])
 * @method \Aws\Result listDatabases(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listDatabasesAsync(array $args = [])
 * @method \Aws\Result listOperations(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listOperationsAsync(array $args = [])
 * @method \Aws\Result listTagsForResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listTagsForResourceAsync(array $args = [])
 * @method \Aws\Result putResourcePermission(array $args = [])
 * @method \GuzzleHttp\Promise\Promise putResourcePermissionAsync(array $args = [])
 * @method \Aws\Result registerApplication(array $args = [])
 * @method \GuzzleHttp\Promise\Promise registerApplicationAsync(array $args = [])
 * @method \Aws\Result startApplicationRefresh(array $args = [])
 * @method \GuzzleHttp\Promise\Promise startApplicationRefreshAsync(array $args = [])
 * @method \Aws\Result tagResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise tagResourceAsync(array $args = [])
 * @method \Aws\Result untagResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise untagResourceAsync(array $args = [])
 * @method \Aws\Result updateApplicationSettings(array $args = [])
 * @method \GuzzleHttp\Promise\Promise updateApplicationSettingsAsync(array $args = [])
 */
class SsmSapClient extends AwsClient {}
