<?php
namespace Aws\SupportApp;

use Aws\AwsClient;

/**
 * This client is used to interact with the **AWS Support App** service.
 * @method \Aws\Result createSlackChannelConfiguration(array $args = [])
 * @method \GuzzleHttp\Promise\Promise createSlackChannelConfigurationAsync(array $args = [])
 * @method \Aws\Result deleteAccountAlias(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteAccountAliasAsync(array $args = [])
 * @method \Aws\Result deleteSlackChannelConfiguration(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteSlackChannelConfigurationAsync(array $args = [])
 * @method \Aws\Result deleteSlackWorkspaceConfiguration(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteSlackWorkspaceConfigurationAsync(array $args = [])
 * @method \Aws\Result getAccountAlias(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getAccountAliasAsync(array $args = [])
 * @method \Aws\Result listSlackChannelConfigurations(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listSlackChannelConfigurationsAsync(array $args = [])
 * @method \Aws\Result listSlackWorkspaceConfigurations(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listSlackWorkspaceConfigurationsAsync(array $args = [])
 * @method \Aws\Result putAccountAlias(array $args = [])
 * @method \GuzzleHttp\Promise\Promise putAccountAliasAsync(array $args = [])
 * @method \Aws\Result registerSlackWorkspaceForOrganization(array $args = [])
 * @method \GuzzleHttp\Promise\Promise registerSlackWorkspaceForOrganizationAsync(array $args = [])
 * @method \Aws\Result updateSlackChannelConfiguration(array $args = [])
 * @method \GuzzleHttp\Promise\Promise updateSlackChannelConfigurationAsync(array $args = [])
 */
class SupportAppClient extends AwsClient {}
