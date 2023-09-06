<?php
namespace Aws\CodeGuruSecurity;

use Aws\AwsClient;

/**
 * This client is used to interact with the **Amazon CodeGuru Security** service.
 * @method \Aws\Result batchGetFindings(array $args = [])
 * @method \GuzzleHttp\Promise\Promise batchGetFindingsAsync(array $args = [])
 * @method \Aws\Result createScan(array $args = [])
 * @method \GuzzleHttp\Promise\Promise createScanAsync(array $args = [])
 * @method \Aws\Result createUploadUrl(array $args = [])
 * @method \GuzzleHttp\Promise\Promise createUploadUrlAsync(array $args = [])
 * @method \Aws\Result getAccountConfiguration(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getAccountConfigurationAsync(array $args = [])
 * @method \Aws\Result getFindings(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getFindingsAsync(array $args = [])
 * @method \Aws\Result getMetricsSummary(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getMetricsSummaryAsync(array $args = [])
 * @method \Aws\Result getScan(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getScanAsync(array $args = [])
 * @method \Aws\Result listFindingsMetrics(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listFindingsMetricsAsync(array $args = [])
 * @method \Aws\Result listScans(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listScansAsync(array $args = [])
 * @method \Aws\Result listTagsForResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listTagsForResourceAsync(array $args = [])
 * @method \Aws\Result tagResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise tagResourceAsync(array $args = [])
 * @method \Aws\Result untagResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise untagResourceAsync(array $args = [])
 * @method \Aws\Result updateAccountConfiguration(array $args = [])
 * @method \GuzzleHttp\Promise\Promise updateAccountConfigurationAsync(array $args = [])
 */
class CodeGuruSecurityClient extends AwsClient {}
