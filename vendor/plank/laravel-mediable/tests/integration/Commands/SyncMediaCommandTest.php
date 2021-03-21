<?php

use Plank\Mediable\Commands\SyncMediaCommand;

class SyncMediaCommandTest extends TestCase
{
    public function test_it_calls_prune_and_install()
    {
        /** @var SyncMediaCommand $command */
        $command = $this->getMockBuilder(SyncMediaCommand::class)
            ->setMethods(['call', 'option', 'argument'])
            ->getMock();
        $command->expects($this->exactly(2))
            ->method('call')
            ->withConsecutive(
                [
                    $this->equalTo('media:prune'),
                    [
                        'disk' => null,
                        '--directory' => '',
                        '--non-recursive' => false,
                    ]
                ],
                [
                    $this->equalTo('media:import'),
                    [
                        'disk' => null,
                        '--directory' => '',
                        '--non-recursive' => false,
                        '--force' => false
                    ]
                ]
            );

        $command->handle();
    }
}
