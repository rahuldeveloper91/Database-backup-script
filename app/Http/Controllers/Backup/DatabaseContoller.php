<?php

namespace App\Http\Controllers\Backup;

use App\Http\Controllers\Controller;
use App\Configuration;

class DatabaseController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Database backup Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling Database backup related tasks.
    |
    */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->configuration = new Configuration();
    }

    /**
     * Take backup of database.
     */
    public function dbBackup()
    {
        if ($this->configuration->isDatabaseBackupSchedulerEnabled()) {
            return redirect(route('home'));
        }

        $this->configuration->enableDatabaseBackupScheduler();

        // Currently no view not required for this.
        \Artisan::call('backup:database');
        echo nl2br(\Artisan::output());
        echo "<a href='".route('home')."'>Click to go back.</a>";
    }

    /**
     * Stop database scheduler.
     */
    public function stopDbBackup()
    {
        $this->configuration->disableDatabaseBackupScheduler();

        return redirect(route('home'));
    }
}
