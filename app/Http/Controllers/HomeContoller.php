<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Configuration;

class HomeContoller extends Controller
{
    /**
     * Take backup of database.
     *
     * @return bool
     */
    public function index()
    {
        $configuration = new Configuration;
        $isDatabaseBackupSchedulerEnabled = $configuration->isDatabaseBackupSchedulerEnabled();

        return view('welcome', compact(['isDatabaseBackupSchedulerEnabled']));
    }
}
