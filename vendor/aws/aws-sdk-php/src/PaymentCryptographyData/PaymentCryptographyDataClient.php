<?php
namespace Aws\PaymentCryptographyData;

use Aws\AwsClient;

/**
 * This client is used to interact with the **Payment Cryptography Data Plane** service.
 * @method \Aws\Result decryptData(array $args = [])
 * @method \GuzzleHttp\Promise\Promise decryptDataAsync(array $args = [])
 * @method \Aws\Result encryptData(array $args = [])
 * @method \GuzzleHttp\Promise\Promise encryptDataAsync(array $args = [])
 * @method \Aws\Result generateCardValidationData(array $args = [])
 * @method \GuzzleHttp\Promise\Promise generateCardValidationDataAsync(array $args = [])
 * @method \Aws\Result generateMac(array $args = [])
 * @method \GuzzleHttp\Promise\Promise generateMacAsync(array $args = [])
 * @method \Aws\Result generatePinData(array $args = [])
 * @method \GuzzleHttp\Promise\Promise generatePinDataAsync(array $args = [])
 * @method \Aws\Result reEncryptData(array $args = [])
 * @method \GuzzleHttp\Promise\Promise reEncryptDataAsync(array $args = [])
 * @method \Aws\Result translatePinData(array $args = [])
 * @method \GuzzleHttp\Promise\Promise translatePinDataAsync(array $args = [])
 * @method \Aws\Result verifyAuthRequestCryptogram(array $args = [])
 * @method \GuzzleHttp\Promise\Promise verifyAuthRequestCryptogramAsync(array $args = [])
 * @method \Aws\Result verifyCardValidationData(array $args = [])
 * @method \GuzzleHttp\Promise\Promise verifyCardValidationDataAsync(array $args = [])
 * @method \Aws\Result verifyMac(array $args = [])
 * @method \GuzzleHttp\Promise\Promise verifyMacAsync(array $args = [])
 * @method \Aws\Result verifyPinData(array $args = [])
 * @method \GuzzleHttp\Promise\Promise verifyPinDataAsync(array $args = [])
 */
class PaymentCryptographyDataClient extends AwsClient {}
