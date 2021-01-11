<p align="center"><a href="https://www.csu.qc.ca" target="_blank"><img src="https://www.csu.qc.ca/wp-content/uploads/2020/01/CSU_logo_Black.png" width="400"></a></p>

<p align="center">

[![CircleCI Status](https://circleci.com/gh/CSU-Booking-Platform/application.svg?style=shield)](https://circleci.com/gh/CSU-Booking-Platform/application)
[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=CSU-Booking-Platform_application&metric=alert_status)](https://sonarcloud.io/dashboard?id=CSU-Booking-Platform_application)
<a href="https://github.com/CSU-Booking-Platform/application/blob/main/LICENSE"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

# About CSU Booking Platform

A tool to improve location and equipment reservation workflow for clubs, students and faculty members.

_You must have the ZenHub Extension installed for your browser to properly our issue board and sprints._

## Installation

### Hard Requirements
- Some web server: `Apache` / `Nginx` / `Caddy`
- `Postgres` > 12
- `PHP` >= 7.4
- PHP extensions you might not have by default: `bcmath, pgsql`
- [composer](https://getcomposer.org/)
- [node and npm](https://www.npmjs.com/get-npm)
- `xdebug` is always a plus

_Use any XAMPP/ MAMP like tools, I recommended `laragon` on win or `valet`/`valet+` on mac_

### First-Time Setup Steps
1. once you clone the repo run `composer fresh`, it will run a batch of the starter commands.
1. Open the `.env` and configure it to work with what you need, _namely the DB connection_
1. Create a database matching the connection details in your `.env`
1. Create the db schema and seed it with random values with `composer mfs`
1. verify your setup is functional with `php artisan self-diagnosis`
1. Access the app through your preferred local web server setup
   _(I like valet+ on macs & laragon on win... ghetto way: php artisan serve with sqlite db)_

##### Need More Details?
- [Laravel Installation](https://laravel.com/docs/8.x/installation)
- [Detailed Installation Steps for old project](https://github.com/alexstojda/SOEN341/wiki/1.-Installation-Instructions)

### Bonus Commands
- When you see DB issues run `php artisan migrate:fresh --seed` (`composer mfs` for short) to quickly recreate the db tables and seed it with random data
- If you just need more random data in the db run `php artisan db:seed`
- When everything goes to hell run `composer fresh` or `composer reset` if you think its related to composer dependencies
- `npm run dev` or `npm run watch` for frontend dev build, the watch command will recompile when files are changed

### Docs for all the things
- [PHP](https://secure.php.net/manual/en/index.php)
- [Laravel](https://laravel.com/docs/8.x)
- [Vue.js](https://v3.vuejs.org/guide/introduction.html)
- [Inertia.js](https://inertiajs.com)
- [Laravel Jetstream](https://jetstream.laravel.com/1.x/introduction.html)
- [TailwindCSS](https://tailwindcss.com/)
- [DevDocs aka ALL DOCS](https://devdocs.io/)

## Usage

### Website
Navigate to your local site. Varies depending on web server. Could be localhost:8000, could be csu.test 🤷

### API
Look at `routes/api.php` file for a quick view of api specific routes that are defined for this application.
[Postman](https://www.postman.com) or [Insomnia](https://insomnia.rest/download/) are good tools for building 
out api endpoint collections.

### Console
Tinker is the primary tool for testing queries, php logic, and debugging weird code... Basically your best friend.
Open it with `php artisan tinker`. It's an interactive repl php shell that makes it quick & easy to dumbass proof your code.

## Testing

### PHPUnit
Yu can run `php artisan test` to run all the test suites.
Normally your IDE can be configured to run specific tests or all of them. Refer to specific IDE documentation.

### Jest
Again, your IDE should recognize these tests and help you create a run configuration for those. To run everything from console use `npm run test`.

### Debugging
For PHP, make sure your install the XDebug PHP extension and configure it properly. I like to use remote autostart to avoid hassle and quickly debug my code. 
- [PHPStorm Setup Tutorial](https://youtu.be/iSSjeelN5NU). 
- [Detailed setup instructions for Windows and PHPStorm](https://github.com/alexstojda/SOEN341/wiki/2.-Debugging-and-Unit-testing)
- Another one: [Xdebug with Docker & VSCode](https://www.youtube.com/watch?v=iloCjuqMdKU)

## Resources

We'll be using the laravel framework for this project. A good place to start learning all of this scary new tech is 
[Laracasts](https://laracasts.com/), specifically the [laravel from scratch series](https://laracasts.com/series/laravel-6-from-scratch)
& [Vue 2 Step by step](https://laracasts.com/series/learn-vue-2-step-by-step).

### Video Tutorials

- [Video Game Aggregator (Laravel+Tailwind)](https://laracasts.com/series/build-a-video-game-aggregator)
- [Vue Step-by-Step](https://laracasts.com/series/learn-vue-2-step-by-step)
- [Testing Laravel](https://vimeo.com/showcase/7060635/video/394206794)
- https://www.youtube.com/watch?v=U3rPtLW5iuI
- https://www.youtube.com/watch?v=EU7PRmCpx-0&list=PLillGF-RfqbYhQsN5WMXy6VsDMKGadrJ-

### Architecture and Design Inspiration

#### Laravel Best Practices and Design Pattern Resources
- [Best Practices Summary Site](http://www.laravelbestpractices.com/#design_patterns)
- [Laravel Team on Laravel 4 patterns](https://www.slideshare.net/sparksphill/software-design-patterns-in-laravel-by-phill-sparks) - 
  [Presentation Talk](https://www.youtube.com/watch?v=qkIsTtIcTBE)
- [Design Patterns in Laravel Book](https://produirebio-normandie.org/wp-content/uploads/2016/01/9781783287987-LARAVEL_DESIGN_PATTERNS_AND_BEST_PRACTICES.pdf)
- [Code examples of patterns](https://github.com/kdocki/larasign) -
  [Here's a video if you don't like reading](https://www.youtube.com/watch?v=qpo5KG0vIyE) 
- [Adapter, Straregy & Factory Patterns in Laravel w/ examples](https://www.youtube.com/watch?v=e4ugSgGaCQ0&index=2&list=PLuCEg9czvGugn72y0kuvxEUvbRc2HHN4J) 
_prepare to 2x speed and hit the left key 8x a sec_
- Design Patterns in PHP and Laravel by Kelt Dockins, ISBN:9781484224502 [link on gdrive coming soon](soon)

## License
This booking application is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
