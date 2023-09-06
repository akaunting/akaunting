<?php
namespace Aws\EMRServerless;

use Aws\AwsClient;

/**
 * This client is used to interact with the **EMR Serverless** service.
 * @method \Aws\Result cancelJobRun(array $args = [])
 * @method \GuzzleHttp\Promise\Promise cancelJobRunAsync(array $args = [])
 * @method \Aws\Result createApplication(array $args = [])
 * @method \GuzzleHttp\Promise\Promise createApplicationAsync(array $args = [])
 * @method \Aws\Result deleteApplication(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteApplicationAsync(array $args = [])
 * @method \Aws\Result getApplication(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getApplicationAsync(array $args = [])
 * @method \Aws\Result getDashboardForJobRun(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getDashboardForJobRunAsync(array $args = [])
 * @method \Aws\Result getJobRun(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getJobRunAsync(array $args = [])
 * @method \Aws\Result listApplications(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listApplicationsAsync(array $args = [])
 * @method \Aws\Result listJobRuns(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listJobRunsAsync(array $args = [])
 * @method \Aws\Result listTagsForResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listTagsForResourceAsync(array $args = [])
 * @method \Aws\Result startApplication(array $args = [])
 * @method \GuzzleHttp\Promise\Promise startApplicationAsync(array $args = [])
 * @method \Aws\Result startJobRun(array $args = [])
 * @method \GuzzleHttp\Promise\Promise startJobRunAsync(array $args = [])
 * @method \Aws\Result stopApplication(array $args = [])
 * @method \GuzzleHttp\Promise\Promise stopApplicationAsync(array $args = [])
 * @method \Aws\Result tagResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise tagResourceAsync(array $args = [])
 * @method \Aws\Result untagResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise untagResourceAsync(array $args = [])
 * @method \Aws\Result updateApplication(array $args = [])
 * @method \GuzzleHttp\Promise\Promise updateApplicationAsync(array $args = [])
 */
class EMRServerlessClient extends AwsClient {}
