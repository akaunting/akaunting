<?php
namespace Aws\SageMakerGeospatial;

use Aws\AwsClient;

/**
 * This client is used to interact with the **Amazon SageMaker geospatial capabilities** service.
 * @method \Aws\Result deleteEarthObservationJob(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteEarthObservationJobAsync(array $args = [])
 * @method \Aws\Result deleteVectorEnrichmentJob(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteVectorEnrichmentJobAsync(array $args = [])
 * @method \Aws\Result exportEarthObservationJob(array $args = [])
 * @method \GuzzleHttp\Promise\Promise exportEarthObservationJobAsync(array $args = [])
 * @method \Aws\Result exportVectorEnrichmentJob(array $args = [])
 * @method \GuzzleHttp\Promise\Promise exportVectorEnrichmentJobAsync(array $args = [])
 * @method \Aws\Result getEarthObservationJob(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getEarthObservationJobAsync(array $args = [])
 * @method \Aws\Result getRasterDataCollection(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getRasterDataCollectionAsync(array $args = [])
 * @method \Aws\Result getTile(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getTileAsync(array $args = [])
 * @method \Aws\Result getVectorEnrichmentJob(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getVectorEnrichmentJobAsync(array $args = [])
 * @method \Aws\Result listEarthObservationJobs(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listEarthObservationJobsAsync(array $args = [])
 * @method \Aws\Result listRasterDataCollections(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listRasterDataCollectionsAsync(array $args = [])
 * @method \Aws\Result listTagsForResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listTagsForResourceAsync(array $args = [])
 * @method \Aws\Result listVectorEnrichmentJobs(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listVectorEnrichmentJobsAsync(array $args = [])
 * @method \Aws\Result searchRasterDataCollection(array $args = [])
 * @method \GuzzleHttp\Promise\Promise searchRasterDataCollectionAsync(array $args = [])
 * @method \Aws\Result startEarthObservationJob(array $args = [])
 * @method \GuzzleHttp\Promise\Promise startEarthObservationJobAsync(array $args = [])
 * @method \Aws\Result startVectorEnrichmentJob(array $args = [])
 * @method \GuzzleHttp\Promise\Promise startVectorEnrichmentJobAsync(array $args = [])
 * @method \Aws\Result stopEarthObservationJob(array $args = [])
 * @method \GuzzleHttp\Promise\Promise stopEarthObservationJobAsync(array $args = [])
 * @method \Aws\Result stopVectorEnrichmentJob(array $args = [])
 * @method \GuzzleHttp\Promise\Promise stopVectorEnrichmentJobAsync(array $args = [])
 * @method \Aws\Result tagResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise tagResourceAsync(array $args = [])
 * @method \Aws\Result untagResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise untagResourceAsync(array $args = [])
 */
class SageMakerGeospatialClient extends AwsClient {}
