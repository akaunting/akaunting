<?php
namespace Aws\MedicalImaging;

use Aws\AwsClient;

/**
 * This client is used to interact with the **AWS Health Imaging** service.
 * @method \Aws\Result copyImageSet(array $args = [])
 * @method \GuzzleHttp\Promise\Promise copyImageSetAsync(array $args = [])
 * @method \Aws\Result createDatastore(array $args = [])
 * @method \GuzzleHttp\Promise\Promise createDatastoreAsync(array $args = [])
 * @method \Aws\Result deleteDatastore(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteDatastoreAsync(array $args = [])
 * @method \Aws\Result deleteImageSet(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteImageSetAsync(array $args = [])
 * @method \Aws\Result getDICOMImportJob(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getDICOMImportJobAsync(array $args = [])
 * @method \Aws\Result getDatastore(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getDatastoreAsync(array $args = [])
 * @method \Aws\Result getImageFrame(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getImageFrameAsync(array $args = [])
 * @method \Aws\Result getImageSet(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getImageSetAsync(array $args = [])
 * @method \Aws\Result getImageSetMetadata(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getImageSetMetadataAsync(array $args = [])
 * @method \Aws\Result listDICOMImportJobs(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listDICOMImportJobsAsync(array $args = [])
 * @method \Aws\Result listDatastores(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listDatastoresAsync(array $args = [])
 * @method \Aws\Result listImageSetVersions(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listImageSetVersionsAsync(array $args = [])
 * @method \Aws\Result listTagsForResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listTagsForResourceAsync(array $args = [])
 * @method \Aws\Result searchImageSets(array $args = [])
 * @method \GuzzleHttp\Promise\Promise searchImageSetsAsync(array $args = [])
 * @method \Aws\Result startDICOMImportJob(array $args = [])
 * @method \GuzzleHttp\Promise\Promise startDICOMImportJobAsync(array $args = [])
 * @method \Aws\Result tagResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise tagResourceAsync(array $args = [])
 * @method \Aws\Result untagResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise untagResourceAsync(array $args = [])
 * @method \Aws\Result updateImageSetMetadata(array $args = [])
 * @method \GuzzleHttp\Promise\Promise updateImageSetMetadataAsync(array $args = [])
 */
class MedicalImagingClient extends AwsClient {}
