<?php
namespace Aws\Account;

use Aws\AwsClient;

/**
 * This client is used to interact with the **AWS Account** service.
 * @method \Aws\Result deleteAlternateContact(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteAlternateContactAsync(array $args = [])
 * @method \Aws\Result disableRegion(array $args = [])
 * @method \GuzzleHttp\Promise\Promise disableRegionAsync(array $args = [])
 * @method \Aws\Result enableRegion(array $args = [])
 * @method \GuzzleHttp\Promise\Promise enableRegionAsync(array $args = [])
 * @method \Aws\Result getAlternateContact(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getAlternateContactAsync(array $args = [])
 * @method \Aws\Result getContactInformation(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getContactInformationAsync(array $args = [])
 * @method \Aws\Result getRegionOptStatus(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getRegionOptStatusAsync(array $args = [])
 * @method \Aws\Result listRegions(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listRegionsAsync(array $args = [])
 * @method \Aws\Result putAlternateContact(array $args = [])
 * @method \GuzzleHttp\Promise\Promise putAlternateContactAsync(array $args = [])
 * @method \Aws\Result putContactInformation(array $args = [])
 * @method \GuzzleHttp\Promise\Promise putContactInformationAsync(array $args = [])
 */
class AccountClient extends AwsClient {}
