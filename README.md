# Grading Book

### Description
Create a grade-book program that reads in all the students’ names and grades, and then computes their
final averages using Tiffy’s grading techniques.  Homework grades are preceded by an ‘H’ and test
grades are preceded by a ‘T’.

### Tech Stack Used

- Frontend
    - CSS
    - HTML
    - Javascript
- Backend
    - Laravel 9 (PHP)
    - MySQL

### How to run

#### Prerequisites
1. PHP - https://www.php.net/manual/en/install.php
2. MySQL - https://www.mysql.com/downloads
3. Git - https://git-scm.com/download/
4. Composer - https://getcomposer.org/download/

#### Installation

To simplify the installation, let's just and serve the files locally instead of serving through a web server like nginx / apache.

1. git clone git@github.com:acelabini/gradebook.git
2. Create database `reviewbuzz`
3. Open project directory in the terminal `cd ./gradebook`
4. Install laravel dependencies, run `composer install`
5. Copy env file `cp .env.example .env`
6. Edit `env` file with the MySQL credentials
7. Generate the app key, run `php artisan key:generate`
8. Run migration script `php artisan migrate`
9. Serve the laravel app, run `php -S localhost:8001 -t public`

### Sample input
```
Quarter 1, 2019
John Wright H 86 55 96 78 T 82 89 93 70 74 H 93 85 80 74 76 82 62
Susan Smith H 75 88 94 95 84 68 91 74 100 82 93 T 73 82 81 92 85
Jane Jones T 88 94 100 82 95 H 84 66 74 98 92 85 100 95 96 42 88
Jimmy Doe H 73 99 98 83 85 92 100 60 74 98 92 T 84 96 79 91 95
Suzy Johnson H 65 72 78 80 82 74 76 0 85 75 76 T 74 79 70 83 78
```
