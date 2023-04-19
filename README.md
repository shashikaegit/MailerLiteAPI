# MailerLiteAPI
This is an example project for using the MailerLite API to perform basic operations such as creating, reading, updating, and deleting subscribers. The project includes sample code written in PHP and uses the Laravel framework to handle API requests and responses.

## Installation

1. Clone the repository to your local machine.
    ```
    git clone https://github.com/shashikaegit/MailerLiteAPI.git
    ```
2. Install the project dependencies using Composer.
   ```
   cd MailerLiteAPI
   composer install
   ```
3. Create a new file .env in the project root directory and copy the contents of .env.example to it.
4. Update the .env file with your database credentials.
5. Migrate the database using Artisan.
   ```
   php artisan migrate
   ```
6. Generate MailerLite API and save it into ``settings`` table using below query
   ```
   INSERT INTO `settings` (`id`, `key`, `value`) VALUES ('1', 'mailerlite_apikey', '[YOUR_API_TOKEN]');
   ```
7. Start the development server.
   ```
   php artisan serve
   ```
   
## Usage
You can now access the project by visiting http://localhost:8000 in your web browser.

## Additional Information

To run the tests for this project, simply execute the following command in your terminal:
```
php artisan test
```
This will run all the tests included in the project and provide feedback on their success or failure.


