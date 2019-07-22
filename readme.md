# Introduction
This application takes the backup of databases using laravel scheduler.


# Setup:
Step 1: Clone this repository.

Step 2: Create .env file and fill in the database credentials and generate key.

Step 3: Run `composer install`.

Step 4: Run `php artisan migrate`.

Step 5: Open config/backup.php file and add details of databases of which backup need to be done. You can also change the path where database backup will be stored in this file. Default path is `/storage/backups/database/`.

Step 6: Run `php artisan serve`.
