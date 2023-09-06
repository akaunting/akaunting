<?php
namespace Aws\DocDBElastic;

use Aws\AwsClient;

/**
 * This client is used to interact with the **Amazon DocumentDB Elastic Clusters** service.
 * @method \Aws\Result createCluster(array $args = [])
 * @method \GuzzleHttp\Promise\Promise createClusterAsync(array $args = [])
 * @method \Aws\Result createClusterSnapshot(array $args = [])
 * @method \GuzzleHttp\Promise\Promise createClusterSnapshotAsync(array $args = [])
 * @method \Aws\Result deleteCluster(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteClusterAsync(array $args = [])
 * @method \Aws\Result deleteClusterSnapshot(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteClusterSnapshotAsync(array $args = [])
 * @method \Aws\Result getCluster(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getClusterAsync(array $args = [])
 * @method \Aws\Result getClusterSnapshot(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getClusterSnapshotAsync(array $args = [])
 * @method \Aws\Result listClusterSnapshots(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listClusterSnapshotsAsync(array $args = [])
 * @method \Aws\Result listClusters(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listClustersAsync(array $args = [])
 * @method \Aws\Result listTagsForResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listTagsForResourceAsync(array $args = [])
 * @method \Aws\Result restoreClusterFromSnapshot(array $args = [])
 * @method \GuzzleHttp\Promise\Promise restoreClusterFromSnapshotAsync(array $args = [])
 * @method \Aws\Result tagResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise tagResourceAsync(array $args = [])
 * @method \Aws\Result untagResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise untagResourceAsync(array $args = [])
 * @method \Aws\Result updateCluster(array $args = [])
 * @method \GuzzleHttp\Promise\Promise updateClusterAsync(array $args = [])
 */
class DocDBElasticClient extends AwsClient {}
