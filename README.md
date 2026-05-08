# Cleanup System

A Laravel-based web application for reporting and managing community cleanup activities. Citizens can report cleanup concerns, personnel can be assigned to handle them, and admins can oversee the process.

## Features

- **Anonymous Reporting**: Citizens can submit cleanup reports without registration
- **User Authentication**: Support for citizens, personnel, and admins
- **Report Management**: Assign reports to teams and personnel
- **Comments and Reactions**: Users can comment on reports and react
- **Messaging System**: Conversations between users
- **Admin Dashboard**: Overview and management of all reports
- **Map Integration**: Location-based reports with coordinates

## Installation

1. Clone the repository
2. Install dependencies: `composer install`
3. Copy `.env.example` to `.env` and configure your database
4. Run migrations: `php artisan migrate`
5. Seed the database: `php artisan db:seed`
6. Build assets: `npm install && npm run build`
7. Serve the application: `php artisan serve`

## Usage

- **Citizens**: Register or report anonymously, view reports
- **Personnel**: View assigned reports, update status
- **Admins**: Manage all reports, assign teams/personnel

## Testing

Run the test suite with: `vendor/bin/pest`

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
