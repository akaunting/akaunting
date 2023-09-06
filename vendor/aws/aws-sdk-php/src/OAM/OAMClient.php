<?php
namespace Aws\OAM;

use Aws\AwsClient;

/**
 * This client is used to interact with the **CloudWatch Observability Access Manager** service.
 * @method \Aws\Result createLink(array $args = [])
 * @method \GuzzleHttp\Promise\Promise createLinkAsync(array $args = [])
 * @method \Aws\Result createSink(array $args = [])
 * @method \GuzzleHttp\Promise\Promise createSinkAsync(array $args = [])
 * @method \Aws\Result deleteLink(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteLinkAsync(array $args = [])
 * @method \Aws\Result deleteSink(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteSinkAsync(array $args = [])
 * @method \Aws\Result getLink(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getLinkAsync(array $args = [])
 * @method \Aws\Result getSink(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getSinkAsync(array $args = [])
 * @method \Aws\Result getSinkPolicy(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getSinkPolicyAsync(array $args = [])
 * @method \Aws\Result listAttachedLinks(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listAttachedLinksAsync(array $args = [])
 * @method \Aws\Result listLinks(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listLinksAsync(array $args = [])
 * @method \Aws\Result listSinks(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listSinksAsync(array $args = [])
 * @method \Aws\Result listTagsForResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listTagsForResourceAsync(array $args = [])
 * @method \Aws\Result putSinkPolicy(array $args = [])
 * @method \GuzzleHttp\Promise\Promise putSinkPolicyAsync(array $args = [])
 * @method \Aws\Result tagResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise tagResourceAsync(array $args = [])
 * @method \Aws\Result untagResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise untagResourceAsync(array $args = [])
 * @method \Aws\Result updateLink(array $args = [])
 * @method \GuzzleHttp\Promise\Promise updateLinkAsync(array $args = [])
 */
class OAMClient extends AwsClient {}
