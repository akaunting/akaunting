<?php
namespace Aws\Scheduler;

use Aws\AwsClient;

/**
 * This client is used to interact with the **Amazon EventBridge Scheduler** service.
 * @method \Aws\Result createSchedule(array $args = [])
 * @method \GuzzleHttp\Promise\Promise createScheduleAsync(array $args = [])
 * @method \Aws\Result createScheduleGroup(array $args = [])
 * @method \GuzzleHttp\Promise\Promise createScheduleGroupAsync(array $args = [])
 * @method \Aws\Result deleteSchedule(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteScheduleAsync(array $args = [])
 * @method \Aws\Result deleteScheduleGroup(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteScheduleGroupAsync(array $args = [])
 * @method \Aws\Result getSchedule(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getScheduleAsync(array $args = [])
 * @method \Aws\Result getScheduleGroup(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getScheduleGroupAsync(array $args = [])
 * @method \Aws\Result listScheduleGroups(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listScheduleGroupsAsync(array $args = [])
 * @method \Aws\Result listSchedules(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listSchedulesAsync(array $args = [])
 * @method \Aws\Result listTagsForResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listTagsForResourceAsync(array $args = [])
 * @method \Aws\Result tagResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise tagResourceAsync(array $args = [])
 * @method \Aws\Result untagResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise untagResourceAsync(array $args = [])
 * @method \Aws\Result updateSchedule(array $args = [])
 * @method \GuzzleHttp\Promise\Promise updateScheduleAsync(array $args = [])
 */
class SchedulerClient extends AwsClient {}
