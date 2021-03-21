<?php

namespace Doctrine\DBAL\Tools\Console\Command;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Platforms\Keywords\DB2Keywords;
use Doctrine\DBAL\Platforms\Keywords\KeywordList;
use Doctrine\DBAL\Platforms\Keywords\MySQL57Keywords;
use Doctrine\DBAL\Platforms\Keywords\MySQL80Keywords;
use Doctrine\DBAL\Platforms\Keywords\MySQLKeywords;
use Doctrine\DBAL\Platforms\Keywords\OracleKeywords;
use Doctrine\DBAL\Platforms\Keywords\PostgreSQL91Keywords;
use Doctrine\DBAL\Platforms\Keywords\PostgreSQL92Keywords;
use Doctrine\DBAL\Platforms\Keywords\PostgreSQLKeywords;
use Doctrine\DBAL\Platforms\Keywords\ReservedKeywordsValidator;
use Doctrine\DBAL\Platforms\Keywords\SQLAnywhere11Keywords;
use Doctrine\DBAL\Platforms\Keywords\SQLAnywhere12Keywords;
use Doctrine\DBAL\Platforms\Keywords\SQLAnywhere16Keywords;
use Doctrine\DBAL\Platforms\Keywords\SQLAnywhereKeywords;
use Doctrine\DBAL\Platforms\Keywords\SQLiteKeywords;
use Doctrine\DBAL\Platforms\Keywords\SQLServer2005Keywords;
use Doctrine\DBAL\Platforms\Keywords\SQLServer2008Keywords;
use Doctrine\DBAL\Platforms\Keywords\SQLServer2012Keywords;
use Doctrine\DBAL\Platforms\Keywords\SQLServerKeywords;
use Doctrine\DBAL\Tools\Console\ConnectionProvider;
use Exception;
use InvalidArgumentException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use function array_keys;
use function assert;
use function count;
use function implode;
use function is_array;
use function is_string;
use function trigger_error;

use const E_USER_DEPRECATED;

class ReservedWordsCommand extends Command
{
    /** @var array<string,class-string<KeywordList>> */
    private $keywordListClasses = [
        'mysql'         => MySQLKeywords::class,
        'mysql57'       => MySQL57Keywords::class,
        'mysql80'       => MySQL80Keywords::class,
        'sqlserver'     => SQLServerKeywords::class,
        'sqlserver2005' => SQLServer2005Keywords::class,
        'sqlserver2008' => SQLServer2008Keywords::class,
        'sqlserver2012' => SQLServer2012Keywords::class,
        'sqlite'        => SQLiteKeywords::class,
        'pgsql'         => PostgreSQLKeywords::class,
        'pgsql91'       => PostgreSQL91Keywords::class,
        'pgsql92'       => PostgreSQL92Keywords::class,
        'oracle'        => OracleKeywords::class,
        'db2'           => DB2Keywords::class,
        'sqlanywhere'   => SQLAnywhereKeywords::class,
        'sqlanywhere11' => SQLAnywhere11Keywords::class,
        'sqlanywhere12' => SQLAnywhere12Keywords::class,
        'sqlanywhere16' => SQLAnywhere16Keywords::class,
    ];

    /** @var ConnectionProvider|null */
    private $connectionProvider;

    public function __construct(?ConnectionProvider $connectionProvider = null)
    {
        parent::__construct();
        $this->connectionProvider = $connectionProvider;
        if ($connectionProvider !== null) {
            return;
        }

        @trigger_error(
            'Not passing a connection provider as the first constructor argument is deprecated',
            E_USER_DEPRECATED
        );
    }

    /**
     * If you want to add or replace a keywords list use this command.
     *
     * @param string                    $name
     * @param class-string<KeywordList> $class
     *
     * @return void
     */
    public function setKeywordListClass($name, $class)
    {
        $this->keywordListClasses[$name] = $class;
    }

    /** @return void */
    protected function configure()
    {
        $this
        ->setName('dbal:reserved-words')
        ->setDescription('Checks if the current database contains identifiers that are reserved.')
        ->setDefinition([
            new InputOption('connection', null, InputOption::VALUE_REQUIRED, 'The named database connection'),
            new InputOption(
                'list',
                'l',
                InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
                'Keyword-List name.'
            ),
        ])
        ->setHelp(<<<EOT
Checks if the current database contains tables and columns
with names that are identifiers in this dialect or in other SQL dialects.

By default SQLite, MySQL, PostgreSQL, Microsoft SQL Server, Oracle
and SQL Anywhere keywords are checked:

    <info>%command.full_name%</info>

If you want to check against specific dialects you can
pass them to the command:

    <info>%command.full_name% -l mysql -l pgsql</info>

The following keyword lists are currently shipped with Doctrine:

    * mysql
    * mysql57
    * mysql80
    * pgsql
    * pgsql92
    * sqlite
    * oracle
    * sqlserver
    * sqlserver2005
    * sqlserver2008
    * sqlserver2012
    * sqlanywhere
    * sqlanywhere11
    * sqlanywhere12
    * sqlanywhere16
    * db2 (Not checked by default)
EOT
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $conn = $this->getConnection($input);

        $keywordLists = $input->getOption('list');

        if (is_string($keywordLists)) {
            $keywordLists = [$keywordLists];
        } elseif (! is_array($keywordLists)) {
            $keywordLists = [];
        }

        if (! $keywordLists) {
            $keywordLists = [
                'mysql',
                'mysql57',
                'mysql80',
                'pgsql',
                'pgsql92',
                'sqlite',
                'oracle',
                'sqlserver',
                'sqlserver2005',
                'sqlserver2008',
                'sqlserver2012',
                'sqlanywhere',
                'sqlanywhere11',
                'sqlanywhere12',
                'sqlanywhere16',
            ];
        }

        $keywords = [];
        foreach ($keywordLists as $keywordList) {
            if (! isset($this->keywordListClasses[$keywordList])) {
                throw new InvalidArgumentException(
                    "There exists no keyword list with name '" . $keywordList . "'. " .
                    'Known lists: ' . implode(', ', array_keys($this->keywordListClasses))
                );
            }

            $class      = $this->keywordListClasses[$keywordList];
            $keywords[] = new $class();
        }

        $output->write(
            'Checking keyword violations for <comment>' . implode(', ', $keywordLists) . '</comment>...',
            true
        );

        $schema  = $conn->getSchemaManager()->createSchema();
        $visitor = new ReservedKeywordsValidator($keywords);
        $schema->visit($visitor);

        $violations = $visitor->getViolations();
        if (count($violations) !== 0) {
            $output->write(
                'There are <error>' . count($violations) . '</error> reserved keyword violations'
                    . ' in your database schema:',
                true
            );

            foreach ($violations as $violation) {
                $output->write('  - ' . $violation, true);
            }

            return 1;
        }

        $output->write('No reserved keywords violations have been found!', true);

        return 0;
    }

    private function getConnection(InputInterface $input): Connection
    {
        $connectionName = $input->getOption('connection');
        assert(is_string($connectionName) || $connectionName === null);

        if ($this->connectionProvider === null) {
            if ($connectionName !== null) {
                throw new Exception('Specifying a connection is only supported when a ConnectionProvider is used.');
            }

            return $this->getHelper('db')->getConnection();
        }

        if ($connectionName !== null) {
            return $this->connectionProvider->getConnection($connectionName);
        }

        return $this->connectionProvider->getDefaultConnection();
    }
}
