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

//TO create seeders
php artisan make:seeder NameSeeder
eg> php artisan make:seeder RoleAndPermissionSeeder
//to seed the database
php artisan db:seed --class=RoleAndPermissionSeeder
