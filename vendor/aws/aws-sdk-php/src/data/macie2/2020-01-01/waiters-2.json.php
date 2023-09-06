<?php
// This file was auto-generated from sdk-root/src/data/macie2/2020-01-01/waiters-2.json
return [ 'version' => 2, 'waiters' => [ 'FindingRevealed' => [ 'description' => 'Wait until the sensitive data occurrences are ready.', 'delay' => 2, 'maxAttempts' => 60, 'operation' => 'GetSensitiveDataOccurrences', 'acceptors' => [ [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'success', 'expected' => 'SUCCESS', ], [ 'matcher' => 'path', 'argument' => 'status', 'state' => 'success', 'expected' => 'ERROR', ], ], ], ],];
