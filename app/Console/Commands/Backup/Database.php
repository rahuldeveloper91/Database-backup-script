<?php

namespace App\Console\Commands\Backup;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class Database extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:database';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to take backup of database.';

    protected $process = [];

    protected $databaseBackupRootPath = '';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->databaseBackupRootPath = config('backup.database_backup_root_path');
        $this->databaseConfig = config('backup.databases');
    }

    /**
     * Create backup file path.
     *
     * @return string
     */
    public function getBackupFilePath()
    {
        $folder = $this->databaseBackupRootPath . $this->database['database'] . '/' . date('Y-m-d');

        if (!file_exists($folder)) {
            mkdir($folder, 0777, true);
        }

        return $folder . '/' . $this->database['database'] . '_' . time() . '.sql';
    }

    /**
     * Create a backup process.
     *
     * @return void
     */
    public function createBackupProcess()
    {
        // Laravel jobs and queues can be used if required.
        $this->process[$this->database['database']] = new Process(sprintf(
            "mysqldump --user=%s --password=%s --host=%s %s > %s",
            $this->database['user'],
            $this->database['password'],
            $this->database['host'],
            $this->database['database'],
            $this->getBackupFilePath()
        ));
    }

    /**
     * Create bacckup.
     *
     * @return void
     */
    public function createBackup()
    {
        $this->createBackupProcess();
        $this->process[$this->database['database']]->mustRun();
        $this->log('The backup for database ' . $this->database['database'] . ' has been proceed successfully.');
        $this->info('The backup for database ' . $this->database['database'] . ' has been proceed successfully.');
    }

    /**
     * Create log.
     *
     * @return void
     */
    public function log($message)
    {
        $logPath = storage_path('logs/scheduler.' . date('Y') . '.logs.log');
        $this->logfile = fopen($logPath, 'a');
        fwrite($this->logfile, date('Y-m-d H:i:s') . ': ' . $message . PHP_EOL);
        fclose($this->logfile);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        foreach ($this->databaseConfig as $this->database) {
            if (!$this->database['status']) {
                continue;
            }
            try {
                $this->createBackup();
            } catch (ProcessFailedException $exception) {
                $this->log('The backup process failed for ' . $this->database['database'] . '.');
                $this->error('The backup process failed for ' . $this->database['database'] . '.');
            } catch (\Exception $e) {
                $this->log('The backup process failed for ' . $this->database['database'] . '.');
                $this->error($e->getMessage());
            }
        }
    }
}
