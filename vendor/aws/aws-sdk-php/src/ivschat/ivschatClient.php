<?php
namespace Aws\ivschat;

use Aws\AwsClient;

/**
 * This client is used to interact with the **Amazon Interactive Video Service Chat** service.
 * @method \Aws\Result createChatToken(array $args = [])
 * @method \GuzzleHttp\Promise\Promise createChatTokenAsync(array $args = [])
 * @method \Aws\Result createLoggingConfiguration(array $args = [])
 * @method \GuzzleHttp\Promise\Promise createLoggingConfigurationAsync(array $args = [])
 * @method \Aws\Result createRoom(array $args = [])
 * @method \GuzzleHttp\Promise\Promise createRoomAsync(array $args = [])
 * @method \Aws\Result deleteLoggingConfiguration(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteLoggingConfigurationAsync(array $args = [])
 * @method \Aws\Result deleteMessage(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteMessageAsync(array $args = [])
 * @method \Aws\Result deleteRoom(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteRoomAsync(array $args = [])
 * @method \Aws\Result disconnectUser(array $args = [])
 * @method \GuzzleHttp\Promise\Promise disconnectUserAsync(array $args = [])
 * @method \Aws\Result getLoggingConfiguration(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getLoggingConfigurationAsync(array $args = [])
 * @method \Aws\Result getRoom(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getRoomAsync(array $args = [])
 * @method \Aws\Result listLoggingConfigurations(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listLoggingConfigurationsAsync(array $args = [])
 * @method \Aws\Result listRooms(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listRoomsAsync(array $args = [])
 * @method \Aws\Result listTagsForResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listTagsForResourceAsync(array $args = [])
 * @method \Aws\Result sendEvent(array $args = [])
 * @method \GuzzleHttp\Promise\Promise sendEventAsync(array $args = [])
 * @method \Aws\Result tagResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise tagResourceAsync(array $args = [])
 * @method \Aws\Result untagResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise untagResourceAsync(array $args = [])
 * @method \Aws\Result updateLoggingConfiguration(array $args = [])
 * @method \GuzzleHttp\Promise\Promise updateLoggingConfigurationAsync(array $args = [])
 * @method \Aws\Result updateRoom(array $args = [])
 * @method \GuzzleHttp\Promise\Promise updateRoomAsync(array $args = [])
 */
class ivschatClient extends AwsClient {}
