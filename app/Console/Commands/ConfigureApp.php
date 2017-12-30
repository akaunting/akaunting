<?php

namespace App\Console\Commands;

use Artisan;
use Session;
use App\Utilities\AppConfigurer;
use Illuminate\Console\Command;

class ConfigureApp extends Command {
	const CMD_SUCCESS = 0;
	const CMD_ERROR = 1;
	const OPT_DB_HOST = 'db-host';
	const OPT_DB_PORT = 'db-port';
	const OPT_DB_NAME = 'db-name';
	const OPT_DB_USERNAME = 'db-username';
	const OPT_DB_PASSWORD = 'db-password';
	const OPT_COMPANY_NAME = 'company-name';
	const OPT_COMPANY_EMAIL = 'company-email';
	const OPT_ADMIN_EMAIL = 'admin-email';
	const OPT_ADMIN_PASSWORD = 'admin-password';
	const OPT_LOCALE = 'locale';
	const OPT_NO_INTERACTION = 'no-interaction';

	var $dbHost;
	var $dbPort;
	var $dbName;
	var $dbUsername;
	var $dbPassword;

	var $companyName;
	var $companyEmail;

	var $adminEmail;
	var $adminPassword;

	var $locale;

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'app:configure 
                            {--db-host= : Database host}
                            {--db-port=3306 : Port of the database host}
                            {--db-name= : Name of the database}
                            {--db-username= : Username to use to access the database}
                            {--db-password= : Password to use to access the database}
                            {--company-name= : Name of the company managed buy the app}
                            {--company-email= : email used to contact the company}
                            {--admin-email= : Admin user email}
                            {--admin-password= : Admin user password}
                            {--locale=en-GB : Language used in the app}
                            ';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Allows to configure the app directly through CLI';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle() {
		$missingOptions = $this->checkOptions();
		if ( ! empty( $missingOptions ) && $this->option( self::OPT_NO_INTERACTION ) ) {
			$this->line( '❌ Some options are missing and --no-interaction is present. Please run the following command for more informations :' );
			$this->line( '❌   php artisan help app:configure' );
			$this->line( '❌ Missing options are : ' . join( ', ', $missingOptions ) );

			return self::CMD_ERROR;
		}

		$this->line( 'Setting locale ' . $this->locale );
		Session::put( self::OPT_LOCALE, $this->locale );

		$this->prompt();

		$this->line( 'Configuring database' );
		if ( ! $this->configureDatabase() ) {
			return self::CMD_ERROR;
		}

		$this->line( 'Configuring company' );
		AppConfigurer::createCompany( $this->companyName, $this->companyEmail, $this->locale );

		$this->line( 'Creating Admin user' );
		AppConfigurer::createUser( $this->adminEmail, $this->adminPassword, $this->locale );

		$this->line( 'Applying the final touches' );
		AppConfigurer::finalTouches();

		return self::CMD_SUCCESS;
	}

	/**
	 * Check that all options are presents. otherwise returns an array of the missing options
	 */
	private function checkOptions() {
		$missingOptions = array();

		$this->locale = $this->option( self::OPT_LOCALE );
		if ( empty( $this->locale ) ) {
			$missingOptions[] = self::OPT_LOCALE;
		}

		$this->dbHost = $this->option( self::OPT_DB_HOST );
		if ( empty( $this->dbHost ) ) {
			$missingOptions[] = self::OPT_DB_HOST;
		}

		$this->dbPort = $this->option( self::OPT_DB_PORT );
		if ( empty( $this->dbPort ) ) {
			$missingOptions[] = self::OPT_DB_PORT;
		}

		$this->dbName = $this->option( self::OPT_DB_NAME );
		if ( empty( $this->dbPort ) ) {
			$missingOptions[] = self::OPT_DB_NAME;
		}

		$this->dbUsername = $this->option( self::OPT_DB_USERNAME );
		if ( empty( $this->dbPort ) ) {
			$missingOptions[] = self::OPT_DB_USERNAME;
		}

		$this->dbPassword = $this->option( self::OPT_DB_PASSWORD );
		if ( empty( $this->dbPort ) ) {
			$missingOptions[] = self::OPT_DB_PASSWORD;
		}

		$this->companyName = $this->option( self::OPT_COMPANY_NAME );
		if ( empty( $this->dbPort ) ) {
			$missingOptions[] = self::OPT_COMPANY_NAME;
		}

		$this->companyEmail = $this->option( self::OPT_COMPANY_EMAIL );
		if ( empty( $this->dbPort ) ) {
			$missingOptions[] = self::OPT_COMPANY_EMAIL;
		}

		$this->adminEmail = $this->option( self::OPT_ADMIN_EMAIL );
		if ( empty( $this->dbPort ) ) {
			$missingOptions[] = self::OPT_ADMIN_EMAIL;
		}

		$this->adminPassword = $this->option( self::OPT_ADMIN_PASSWORD );
		if ( empty( $this->dbPort ) ) {
			$missingOptions[] = self::OPT_ADMIN_PASSWORD;
		}

		return $missingOptions;
	}

	/**
	 * Ask the user for data if some options are missing.
	 */
	private function prompt() {
		if ( empty( $this->dbHost ) ) {
			$this->dbHost = $this->ask( 'What is the database host?', 'localhost' );
		}

		if ( empty( $this->dbPort ) ) {
			$this->dbPort = $this->ask( 'What is the database port?', '3606' );
		}

		if ( empty( $this->dbName ) ) {
			$this->dbName = $this->ask( 'What is the database name?' );
		}

		if ( empty( $this->dbUsername ) ) {
			$this->dbUsername = $this->ask( 'What is the database username?' );
		}

		if ( empty( $this->dbPassword ) ) {
			$this->dbPassword = $this->secret( 'What is the database password?' );
		}

		if ( empty( $this->companyName ) ) {
			$this->companyName = $this->ask( 'What is the company name?' );
		}

		if ( empty( $this->companyEmail ) ) {
			$this->companyEmail = $this->ask( 'What is the company contact email?' );
		}

		if ( empty( $this->adminEmail ) ) {
			$this->adminEmail = $this->ask( 'What is the admin email?', $this->companyEmail );
		}

		if ( empty( $this->adminPassword ) ) {
			$this->adminPassword = $this->secret( 'What is the admin password?' );
		}
	}

	private function configureDatabase() {
		$this->dbHost     = $this->option( self::OPT_DB_HOST );
		$this->dbPort     = $this->option( self::OPT_DB_PORT );
		$this->dbName     = $this->option( self::OPT_DB_NAME );
		$this->dbUsername = $this->option( self::OPT_DB_USERNAME );
		$this->dbPassword = $this->option( self::OPT_DB_PASSWORD );

		$this->line( 'Connecting to database ' . $this->dbName . '@' . $this->dbHost . ':' . $this->dbPort );
		$isDbValid = AppConfigurer::isDbValid( $this->dbHost, $this->dbPort, $this->dbName, $this->dbUsername, $this->dbPassword );
		if ( ! $isDbValid ) {
			$this->error( 'Error: Could not connect to the database! Please, make sure the details are correct.' );

			return false;
		}

		AppConfigurer::saveDbVariables( $this->dbHost, $this->dbPort, $this->dbName, $this->dbUsername, $this->dbPassword );

		// Try to increase the maximum execution time
		set_time_limit( 300 ); // 5 minutes

		// Create tables
		Artisan::call( 'migrate', [ '--force' => true ] );

		// Create Roles
		Artisan::call( 'db:seed', [ '--class' => 'Database\Seeds\Roles', '--force' => true ] );

		return true;
	}
}
