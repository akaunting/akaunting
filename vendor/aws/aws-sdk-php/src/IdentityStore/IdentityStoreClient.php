<?php
namespace Aws\IdentityStore;

use Aws\AwsClient;

/**
 * This client is used to interact with the **AWS SSO Identity Store** service.
 * @method \Aws\Result createGroup(array $args = [])
 * @method \GuzzleHttp\Promise\Promise createGroupAsync(array $args = [])
 * @method \Aws\Result createGroupMembership(array $args = [])
 * @method \GuzzleHttp\Promise\Promise createGroupMembershipAsync(array $args = [])
 * @method \Aws\Result createUser(array $args = [])
 * @method \GuzzleHttp\Promise\Promise createUserAsync(array $args = [])
 * @method \Aws\Result deleteGroup(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteGroupAsync(array $args = [])
 * @method \Aws\Result deleteGroupMembership(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteGroupMembershipAsync(array $args = [])
 * @method \Aws\Result deleteUser(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteUserAsync(array $args = [])
 * @method \Aws\Result describeGroup(array $args = [])
 * @method \GuzzleHttp\Promise\Promise describeGroupAsync(array $args = [])
 * @method \Aws\Result describeGroupMembership(array $args = [])
 * @method \GuzzleHttp\Promise\Promise describeGroupMembershipAsync(array $args = [])
 * @method \Aws\Result describeUser(array $args = [])
 * @method \GuzzleHttp\Promise\Promise describeUserAsync(array $args = [])
 * @method \Aws\Result getGroupId(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getGroupIdAsync(array $args = [])
 * @method \Aws\Result getGroupMembershipId(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getGroupMembershipIdAsync(array $args = [])
 * @method \Aws\Result getUserId(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getUserIdAsync(array $args = [])
 * @method \Aws\Result isMemberInGroups(array $args = [])
 * @method \GuzzleHttp\Promise\Promise isMemberInGroupsAsync(array $args = [])
 * @method \Aws\Result listGroupMemberships(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listGroupMembershipsAsync(array $args = [])
 * @method \Aws\Result listGroupMembershipsForMember(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listGroupMembershipsForMemberAsync(array $args = [])
 * @method \Aws\Result listGroups(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listGroupsAsync(array $args = [])
 * @method \Aws\Result listUsers(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listUsersAsync(array $args = [])
 * @method \Aws\Result updateGroup(array $args = [])
 * @method \GuzzleHttp\Promise\Promise updateGroupAsync(array $args = [])
 * @method \Aws\Result updateUser(array $args = [])
 * @method \GuzzleHttp\Promise\Promise updateUserAsync(array $args = [])
 */
class IdentityStoreClient extends AwsClient {}
