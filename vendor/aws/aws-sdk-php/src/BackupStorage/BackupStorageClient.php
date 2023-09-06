<?php
namespace Aws\BackupStorage;

use Aws\AwsClient;

/**
 * This client is used to interact with the **AWS Backup Storage** service.
 * @method \Aws\Result deleteObject(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteObjectAsync(array $args = [])
 * @method \Aws\Result getChunk(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getChunkAsync(array $args = [])
 * @method \Aws\Result getObjectMetadata(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getObjectMetadataAsync(array $args = [])
 * @method \Aws\Result listChunks(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listChunksAsync(array $args = [])
 * @method \Aws\Result listObjects(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listObjectsAsync(array $args = [])
 * @method \Aws\Result notifyObjectComplete(array $args = [])
 * @method \GuzzleHttp\Promise\Promise notifyObjectCompleteAsync(array $args = [])
 * @method \Aws\Result putChunk(array $args = [])
 * @method \GuzzleHttp\Promise\Promise putChunkAsync(array $args = [])
 * @method \Aws\Result putObject(array $args = [])
 * @method \GuzzleHttp\Promise\Promise putObjectAsync(array $args = [])
 * @method \Aws\Result startObject(array $args = [])
 * @method \GuzzleHttp\Promise\Promise startObjectAsync(array $args = [])
 */
class BackupStorageClient extends AwsClient {}
