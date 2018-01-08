<?php

namespace App\Http\Controllers\Install;

use Artisan;
use App\Http\Requests\Install\Database as Request;
use App\Utilities\AppConfigurer;
use Illuminate\Routing\Controller;

class Database extends Controller {
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create() {
		return view( 'install.database.create' );
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  Request $request
	 *
	 * @return Response
	 */
	public function store( Request $request ) {
		$host = $request['hostname'];
		$port     = env( 'DB_PORT', '3306' );
		$database = $request['database'];
		$username = $request['username'];
		$password = $request['password'];

		// Check database connection
		if ( ! AppConfigurer::isDbValid($host,$port,$database,$username,$password) ) {
			$message = trans( 'install.error.connection' );

			flash( $message )->error()->important();

			return redirect( 'install/database' )->withInput();
		}

		// Set database details
		AppConfigurer::saveDbVariables($host, $port, $database, $username, $password);

		// Try to increase the maximum execution time
		set_time_limit( 300 ); // 5 minutes

		// Create tables
		Artisan::call( 'migrate', [ '--force' => true ] );

		// Create Roles
		Artisan::call( 'db:seed', [ '--class' => 'Database\Seeds\Roles', '--force' => true ] );

		return redirect( 'install/settings' );
	}

}
