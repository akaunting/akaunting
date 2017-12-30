<?php

namespace App\Http\Controllers\Install;

use File;
use App\Utilities\AppConfigurer;
use Illuminate\Routing\Controller;

class Requirements extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function show()
    {
        // Check requirements
        $requirements = AppConfigurer::checkServerRequirements();

        if (empty($requirements)) {
            // Create the .env file
            if (!File::exists(base_path('.env'))) {
	            AppConfigurer::createDefaultEnvFile();
            }

            redirect('install/language')->send();
        } else {
            foreach ($requirements as $requirement) {
                flash($requirement)->error()->important();
            }

            return view('install.requirements.show');
        }
    }
}
