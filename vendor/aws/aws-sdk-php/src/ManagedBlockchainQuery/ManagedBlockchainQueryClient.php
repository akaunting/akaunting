<?php
namespace Aws\ManagedBlockchainQuery;

use Aws\AwsClient;

/**
 * This client is used to interact with the **Amazon Managed Blockchain Query** service.
 * @method \Aws\Result batchGetTokenBalance(array $args = [])
 * @method \GuzzleHttp\Promise\Promise batchGetTokenBalanceAsync(array $args = [])
 * @method \Aws\Result getTokenBalance(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getTokenBalanceAsync(array $args = [])
 * @method \Aws\Result getTransaction(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getTransactionAsync(array $args = [])
 * @method \Aws\Result listTokenBalances(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listTokenBalancesAsync(array $args = [])
 * @method \Aws\Result listTransactionEvents(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listTransactionEventsAsync(array $args = [])
 * @method \Aws\Result listTransactions(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listTransactionsAsync(array $args = [])
 */
class ManagedBlockchainQueryClient extends AwsClient {}
