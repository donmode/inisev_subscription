## About Subscription

This application allows users to subscribe to a website and get email notifications on new posts.

Steps to get the application up and running

-   Create a .env file in the application's root directory and copy .env.example to it
-   Create your mysql database and put the details where necessary in the .env file
-   The Queue_Connection used for this app is 'database' so it's set so.
-   Set up the Mail Configuration credentials as required.
-   Run `php artisan migrate` to migrate the tables
-   Run `php artisan optimize` to clear caches
-   Run `php artisan serve` to serve the app on a terminal
-   Open another terminal and cd into the app's root directory,
-   Then run `php artisan queue:work --timeout=500` to get the queue ready to take any job.
