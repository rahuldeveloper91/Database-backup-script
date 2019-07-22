<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
    protected $fillable = ['setting', 'value'];

    public $timestamps = false;

    protected $primaryKey = 'setting';

    private $settingNames = [
        'enable_database_backup' => 'enable_database_backup'
    ];

    /**
     * To enable database backup.
     *
     * @return bool
     */
    public function enableDatabaseBackupScheduler()
    {
        return $this->updateOrCreate([
            'setting' => $this->settingNames['enable_database_backup'],
        ], [
            'setting' => $this->settingNames['enable_database_backup'],
            'value' => '1'
        ]);
    }

    /**
     * To enable database backup.
     *
     * @return bool
     */
    public function disableDatabaseBackupScheduler()
    {
        return $this->updateOrCreate([
            'setting' => $this->settingNames['enable_database_backup'],
        ], [
            'setting' => $this->settingNames['enable_database_backup'],
            'value' => '0'
        ]);
    }

    /**
     * Check if database backup is enabled or not.
     *
     * @return bool
     */
    public function isDatabaseBackupSchedulerEnabled()
    {
        try {
            return $this->Where([
                'setting' => $this->settingNames['enable_database_backup'],
                'value' => '1'
            ])->first();
        } catch (\Exception $e) {
            return false;
        }
    }
}
