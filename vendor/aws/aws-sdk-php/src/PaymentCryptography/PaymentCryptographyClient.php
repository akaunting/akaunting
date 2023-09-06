<?php
namespace Aws\PaymentCryptography;

use Aws\AwsClient;

/**
 * This client is used to interact with the **Payment Cryptography Control Plane** service.
 * @method \Aws\Result createAlias(array $args = [])
 * @method \GuzzleHttp\Promise\Promise createAliasAsync(array $args = [])
 * @method \Aws\Result createKey(array $args = [])
 * @method \GuzzleHttp\Promise\Promise createKeyAsync(array $args = [])
 * @method \Aws\Result deleteAlias(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteAliasAsync(array $args = [])
 * @method \Aws\Result deleteKey(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteKeyAsync(array $args = [])
 * @method \Aws\Result exportKey(array $args = [])
 * @method \GuzzleHttp\Promise\Promise exportKeyAsync(array $args = [])
 * @method \Aws\Result getAlias(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getAliasAsync(array $args = [])
 * @method \Aws\Result getKey(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getKeyAsync(array $args = [])
 * @method \Aws\Result getParametersForExport(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getParametersForExportAsync(array $args = [])
 * @method \Aws\Result getParametersForImport(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getParametersForImportAsync(array $args = [])
 * @method \Aws\Result getPublicKeyCertificate(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getPublicKeyCertificateAsync(array $args = [])
 * @method \Aws\Result importKey(array $args = [])
 * @method \GuzzleHttp\Promise\Promise importKeyAsync(array $args = [])
 * @method \Aws\Result listAliases(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listAliasesAsync(array $args = [])
 * @method \Aws\Result listKeys(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listKeysAsync(array $args = [])
 * @method \Aws\Result listTagsForResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listTagsForResourceAsync(array $args = [])
 * @method \Aws\Result restoreKey(array $args = [])
 * @method \GuzzleHttp\Promise\Promise restoreKeyAsync(array $args = [])
 * @method \Aws\Result startKeyUsage(array $args = [])
 * @method \GuzzleHttp\Promise\Promise startKeyUsageAsync(array $args = [])
 * @method \Aws\Result stopKeyUsage(array $args = [])
 * @method \GuzzleHttp\Promise\Promise stopKeyUsageAsync(array $args = [])
 * @method \Aws\Result tagResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise tagResourceAsync(array $args = [])
 * @method \Aws\Result untagResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise untagResourceAsync(array $args = [])
 * @method \Aws\Result updateAlias(array $args = [])
 * @method \GuzzleHttp\Promise\Promise updateAliasAsync(array $args = [])
 */
class PaymentCryptographyClient extends AwsClient {}
