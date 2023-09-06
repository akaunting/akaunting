<?php declare(strict_types=1);
/*
 * This file is part of PHPLOC.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace SebastianBergmann\PHPLOC;

use SebastianBergmann\CliParser\Exception as CliParserException;
use SebastianBergmann\CliParser\Parser as CliParser;

final class ArgumentsBuilder
{
    /**
     * @throws ArgumentsBuilderException
     */
    public function build(array $argv): Arguments
    {
        try {
            $options = (new CliParser)->parse(
                $argv,
                'hv',
                [
                    'suffix=',
                    'exclude=',
                    'count-tests',
                    'log-csv=',
                    'log-json=',
                    'log-xml=',
                    'help',
                    'version',
                ]
            );
        } catch (CliParserException $e) {
            throw new ArgumentsBuilderException(
                $e->getMessage(),
                (int) $e->getCode(),
                $e
            );
        }

        $directories = $options[1];
        $exclude     = [];
        $suffixes    = ['.php'];
        $countTests  = false;
        $csvLogfile  = null;
        $jsonLogfile = null;
        $xmlLogfile  = null;
        $help        = false;
        $version     = false;

        foreach ($options[0] as $option) {
            switch ($option[0]) {
                case '--suffix':
                    $suffixes[] = $option[1];

                    break;

                case '--exclude':
                    $exclude[] = $option[1];

                    break;

                case '--count-tests':
                    $countTests = true;

                    break;

                case '--log-csv':
                    $csvLogfile = $option[1];

                    break;

                case '--log-json':
                    $jsonLogfile = $option[1];

                    break;

                case '--log-xml':
                    $xmlLogfile = $option[1];

                    break;

                case 'h':
                case '--help':
                    $help = true;

                    break;

                case 'v':
                case '--version':
                    $version = true;

                    break;
            }
        }

        if (empty($options[1]) && !$help && !$version) {
            throw new ArgumentsBuilderException(
                'No directory specified'
            );
        }

        return new Arguments(
            $directories,
            $suffixes,
            $exclude,
            $countTests,
            $csvLogfile,
            $jsonLogfile,
            $xmlLogfile,
            $help,
            $version,
        );
    }
}
