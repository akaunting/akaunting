<?php
namespace Aws\PI;

use Aws\AwsClient;

/**
 * This client is used to interact with the **AWS Performance Insights** service.
 * @method \Aws\Result createPerformanceAnalysisReport(array $args = [])
 * @method \GuzzleHttp\Promise\Promise createPerformanceAnalysisReportAsync(array $args = [])
 * @method \Aws\Result deletePerformanceAnalysisReport(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deletePerformanceAnalysisReportAsync(array $args = [])
 * @method \Aws\Result describeDimensionKeys(array $args = [])
 * @method \GuzzleHttp\Promise\Promise describeDimensionKeysAsync(array $args = [])
 * @method \Aws\Result getDimensionKeyDetails(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getDimensionKeyDetailsAsync(array $args = [])
 * @method \Aws\Result getPerformanceAnalysisReport(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getPerformanceAnalysisReportAsync(array $args = [])
 * @method \Aws\Result getResourceMetadata(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getResourceMetadataAsync(array $args = [])
 * @method \Aws\Result getResourceMetrics(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getResourceMetricsAsync(array $args = [])
 * @method \Aws\Result listAvailableResourceDimensions(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listAvailableResourceDimensionsAsync(array $args = [])
 * @method \Aws\Result listAvailableResourceMetrics(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listAvailableResourceMetricsAsync(array $args = [])
 * @method \Aws\Result listPerformanceAnalysisReports(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listPerformanceAnalysisReportsAsync(array $args = [])
 * @method \Aws\Result listTagsForResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listTagsForResourceAsync(array $args = [])
 * @method \Aws\Result tagResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise tagResourceAsync(array $args = [])
 * @method \Aws\Result untagResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise untagResourceAsync(array $args = [])
 */
class PIClient extends AwsClient {}
