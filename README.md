requirements
XAMPP or (mysql and php)
composer
nodejs
git for windows

after you clone the project from github link
https://github.com/gnongkynrih/fullstack-2026.git
run this command
npm install
npm run dev
composer install or composer update
php artisan key:generate

Setup
to create a project
composer global require laravel/installer
laravel new projectname
or
composer create-project larave/laravel projname
cd projname
npm install
npm run dev
composer install
//install livewire
composer require livewire/livewire
//to create a new livewire component
php artisan make:livewire componentName
eg> php artisan make:livewire MenuItemManagement

//to use icons we can take heroicons
https://heroicons.com/

//build the js and css
npm run build
//to automatically refresh the pages on changes
npm run dev

//for ui components we will use maryui
https://mary-ui.com/docs/installation
composer require robsontenorio/mary
php artisan mary:install

//to connect to your database edit the .env file
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=msme_hotel
DB_USERNAME=yourusername
DB_PASSWORD=yourpassword

//create migration file along with model
php artisan make:model MenuCategory -m
//to migrate the table
php artisan migrate

//if we want to add more column to existing tables
php artisan make:migration add_column_to_table_name --table=table_name
eg. php artisan make:migration add_column_notes_to_orders --table=orders
//relationships
we define relationships in models
eg. for belongsto we use singular name
eg for hasmany we use plural name

ROLE BASED ACCESS CONTROL
https://spatie.be/docs/laravel-permission/v6/installation-laravel
install the package
composer require spatie/laravel-permission
//publish the migration
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
//run the migration
php artisan migrate
// The User model requires this trait
use HasRoles;
//MIDDLEWARE
// in the laravel bootstrap/app.php file
add
->withMiddleware(function (Middleware $middleware): void {
$middleware->alias([
'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
]);
})
//TO create seeders
php artisan make:seeder NameSeeder
eg> php artisan make:seeder RoleAndPermissionSeeder
//to seed the database
php artisan db:seed --class=RoleAndPermissionSeeder

//TO TEST EMAIL
https://github.com/mailhog/MailHog
after installing we can use it as smtp server and edit the .env file
MAIL_MAILER=smtp
MAIL_HOST=localhost
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null

//FOR ANIMATION WE WILL USE
https://animate.style/
add this in the head tag of the layout

<head>
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
  />
</head>

//payment gateway
https://razorpay.com/
register
siginup
https://accounts.razorpay.com/auth/?redirecturl=https%3A%2F%2Feasy.razorpay.com&auth_intent=signup&x-country-code=IN

//to install razorpay
composer require razorpay/razorpay

//to publish the migration
php artisan vendor:publish --provider="Razorpay\RazorpayServiceProvider"
//run the migration
php artisan migrate

When prompted for the UPI ID / VPA (Virtual Payment Address), enter one of these special test VPAs:For successful payment: success@razorpay
(This simulates an instant successful transaction.)
For failed payment: failure@razorpay
(This instantly triggers a declined/failed transaction.)

for exporting to excel
https://docs.laravel-excel.com/3.1/getting-started/installation.html
composer require "maatwebsite/excel"
php artisan vendor:publish --provider="Maatwebsite\Excel\ExcelServiceProvider" --tag=config

for exporting to pdf
https://github.com/barryvdh/laravel-dompdf
composer require dompdf/dompdf
