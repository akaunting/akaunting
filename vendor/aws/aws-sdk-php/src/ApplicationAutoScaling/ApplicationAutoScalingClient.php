<?php
namespace Aws\ApplicationAutoScaling;

use Aws\AwsClient;

/**
 * This client is used to interact with the **Application Auto Scaling** service.
 * @method \Aws\Result deleteScalingPolicy(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteScalingPolicyAsync(array $args = [])
 * @method \Aws\Result deleteScheduledAction(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteScheduledActionAsync(array $args = [])
 * @method \Aws\Result deregisterScalableTarget(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deregisterScalableTargetAsync(array $args = [])
 * @method \Aws\Result describeScalableTargets(array $args = [])
 * @method \GuzzleHttp\Promise\Promise describeScalableTargetsAsync(array $args = [])
 * @method \Aws\Result describeScalingActivities(array $args = [])
 * @method \GuzzleHttp\Promise\Promise describeScalingActivitiesAsync(array $args = [])
 * @method \Aws\Result describeScalingPolicies(array $args = [])
 * @method \GuzzleHttp\Promise\Promise describeScalingPoliciesAsync(array $args = [])
 * @method \Aws\Result describeScheduledActions(array $args = [])
 * @method \GuzzleHttp\Promise\Promise describeScheduledActionsAsync(array $args = [])
 * @method \Aws\Result listTagsForResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listTagsForResourceAsync(array $args = [])
 * @method \Aws\Result putScalingPolicy(array $args = [])
 * @method \GuzzleHttp\Promise\Promise putScalingPolicyAsync(array $args = [])
 * @method \Aws\Result putScheduledAction(array $args = [])
 * @method \GuzzleHttp\Promise\Promise putScheduledActionAsync(array $args = [])
 * @method \Aws\Result registerScalableTarget(array $args = [])
 * @method \GuzzleHttp\Promise\Promise registerScalableTargetAsync(array $args = [])
 * @method \Aws\Result tagResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise tagResourceAsync(array $args = [])
 * @method \Aws\Result untagResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise untagResourceAsync(array $args = [])
 */
class ApplicationAutoScalingClient extends AwsClient {}
