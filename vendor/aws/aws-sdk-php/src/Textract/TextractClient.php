<?php
namespace Aws\Textract;

use Aws\AwsClient;

/**
 * This client is used to interact with the **Amazon Textract** service.
 * @method \Aws\Result analyzeDocument(array $args = [])
 * @method \GuzzleHttp\Promise\Promise analyzeDocumentAsync(array $args = [])
 * @method \Aws\Result analyzeExpense(array $args = [])
 * @method \GuzzleHttp\Promise\Promise analyzeExpenseAsync(array $args = [])
 * @method \Aws\Result analyzeID(array $args = [])
 * @method \GuzzleHttp\Promise\Promise analyzeIDAsync(array $args = [])
 * @method \Aws\Result detectDocumentText(array $args = [])
 * @method \GuzzleHttp\Promise\Promise detectDocumentTextAsync(array $args = [])
 * @method \Aws\Result getDocumentAnalysis(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getDocumentAnalysisAsync(array $args = [])
 * @method \Aws\Result getDocumentTextDetection(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getDocumentTextDetectionAsync(array $args = [])
 * @method \Aws\Result getExpenseAnalysis(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getExpenseAnalysisAsync(array $args = [])
 * @method \Aws\Result getLendingAnalysis(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getLendingAnalysisAsync(array $args = [])
 * @method \Aws\Result getLendingAnalysisSummary(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getLendingAnalysisSummaryAsync(array $args = [])
 * @method \Aws\Result startDocumentAnalysis(array $args = [])
 * @method \GuzzleHttp\Promise\Promise startDocumentAnalysisAsync(array $args = [])
 * @method \Aws\Result startDocumentTextDetection(array $args = [])
 * @method \GuzzleHttp\Promise\Promise startDocumentTextDetectionAsync(array $args = [])
 * @method \Aws\Result startExpenseAnalysis(array $args = [])
 * @method \GuzzleHttp\Promise\Promise startExpenseAnalysisAsync(array $args = [])
 * @method \Aws\Result startLendingAnalysis(array $args = [])
 * @method \GuzzleHttp\Promise\Promise startLendingAnalysisAsync(array $args = [])
 */
class TextractClient extends AwsClient {}
