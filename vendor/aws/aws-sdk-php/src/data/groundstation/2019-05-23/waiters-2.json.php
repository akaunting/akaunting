<?php
// This file was auto-generated from sdk-root/src/data/groundstation/2019-05-23/waiters-2.json
return [ 'version' => 2, 'waiters' => [ 'ContactScheduled' => [ 'description' => 'Waits until a contact has been scheduled', 'delay' => 5, 'maxAttempts' => 180, 'operation' => 'DescribeContact', 'acceptors' => [ [ 'matcher' => 'path', 'argument' => 'contactStatus', 'state' => 'failure', 'expected' => 'FAILED_TO_SCHEDULE', ], [ 'matcher' => 'path', 'argument' => 'contactStatus', 'state' => 'success', 'expected' => 'SCHEDULED', ], ], ], ],];
