<?php
namespace Aws\TimestreamWrite;

use Aws\AwsClient;

/**
 * This client is used to interact with the **Amazon Timestream Write** service.
 * @method \Aws\Result createBatchLoadTask(array $args = [])
 * @method \GuzzleHttp\Promise\Promise createBatchLoadTaskAsync(array $args = [])
 * @method \Aws\Result createDatabase(array $args = [])
 * @method \GuzzleHttp\Promise\Promise createDatabaseAsync(array $args = [])
 * @method \Aws\Result createTable(array $args = [])
 * @method \GuzzleHttp\Promise\Promise createTableAsync(array $args = [])
 * @method \Aws\Result deleteDatabase(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteDatabaseAsync(array $args = [])
 * @method \Aws\Result deleteTable(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteTableAsync(array $args = [])
 * @method \Aws\Result describeBatchLoadTask(array $args = [])
 * @method \GuzzleHttp\Promise\Promise describeBatchLoadTaskAsync(array $args = [])
 * @method \Aws\Result describeDatabase(array $args = [])
 * @method \GuzzleHttp\Promise\Promise describeDatabaseAsync(array $args = [])
 * @method \Aws\Result describeEndpoints(array $args = [])
 * @method \GuzzleHttp\Promise\Promise describeEndpointsAsync(array $args = [])
 * @method \Aws\Result describeTable(array $args = [])
 * @method \GuzzleHttp\Promise\Promise describeTableAsync(array $args = [])
 * @method \Aws\Result listBatchLoadTasks(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listBatchLoadTasksAsync(array $args = [])
 * @method \Aws\Result listDatabases(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listDatabasesAsync(array $args = [])
 * @method \Aws\Result listTables(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listTablesAsync(array $args = [])
 * @method \Aws\Result listTagsForResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listTagsForResourceAsync(array $args = [])
 * @method \Aws\Result resumeBatchLoadTask(array $args = [])
 * @method \GuzzleHttp\Promise\Promise resumeBatchLoadTaskAsync(array $args = [])
 * @method \Aws\Result tagResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise tagResourceAsync(array $args = [])
 * @method \Aws\Result untagResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise untagResourceAsync(array $args = [])
 * @method \Aws\Result updateDatabase(array $args = [])
 * @method \GuzzleHttp\Promise\Promise updateDatabaseAsync(array $args = [])
 * @method \Aws\Result updateTable(array $args = [])
 * @method \GuzzleHttp\Promise\Promise updateTableAsync(array $args = [])
 * @method \Aws\Result writeRecords(array $args = [])
 * @method \GuzzleHttp\Promise\Promise writeRecordsAsync(array $args = [])
 */
class TimestreamWriteClient extends AwsClient {}
