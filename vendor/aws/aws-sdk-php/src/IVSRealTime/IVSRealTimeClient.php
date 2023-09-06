<?php
namespace Aws\IVSRealTime;

use Aws\AwsClient;

/**
 * This client is used to interact with the **Amazon Interactive Video Service RealTime** service.
 * @method \Aws\Result createParticipantToken(array $args = [])
 * @method \GuzzleHttp\Promise\Promise createParticipantTokenAsync(array $args = [])
 * @method \Aws\Result createStage(array $args = [])
 * @method \GuzzleHttp\Promise\Promise createStageAsync(array $args = [])
 * @method \Aws\Result deleteStage(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteStageAsync(array $args = [])
 * @method \Aws\Result disconnectParticipant(array $args = [])
 * @method \GuzzleHttp\Promise\Promise disconnectParticipantAsync(array $args = [])
 * @method \Aws\Result getParticipant(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getParticipantAsync(array $args = [])
 * @method \Aws\Result getStage(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getStageAsync(array $args = [])
 * @method \Aws\Result getStageSession(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getStageSessionAsync(array $args = [])
 * @method \Aws\Result listParticipantEvents(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listParticipantEventsAsync(array $args = [])
 * @method \Aws\Result listParticipants(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listParticipantsAsync(array $args = [])
 * @method \Aws\Result listStageSessions(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listStageSessionsAsync(array $args = [])
 * @method \Aws\Result listStages(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listStagesAsync(array $args = [])
 * @method \Aws\Result listTagsForResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listTagsForResourceAsync(array $args = [])
 * @method \Aws\Result tagResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise tagResourceAsync(array $args = [])
 * @method \Aws\Result untagResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise untagResourceAsync(array $args = [])
 * @method \Aws\Result updateStage(array $args = [])
 * @method \GuzzleHttp\Promise\Promise updateStageAsync(array $args = [])
 */
class IVSRealTimeClient extends AwsClient {}
