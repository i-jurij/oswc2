# Simple CMS project

This is a application built using [Nette](https://nette.org).

## Requirements

This Web Project is compatible with Nette 3.2 and requires PHP 8.1.  

## Installation

You need [Composer](https://getcomposer.org/) and [npm](https://nodejs.org/en/learn/getting-started/an-introduction-to-the-npm-package-manager) or `yarn` or `pnpm`.  

Copy project in your path:   

	git clone https://github.com/ijurij/oswc2.git
	cd oswc2
	composer install
	npm install

Ensure the `temp/` and `log/` directories are writable.

## Web Server Setup

To quickly dive in, use PHP's built-in server:

	php -S localhost:8000 -t www

Then, open `http://localhost:8000` in your browser to view the welcome page.

For Apache or Nginx users, configure a virtual host pointing to your project's `www/` directory.

**Important Note:** Ensure `app/`, `config/`, `log/`, and `temp/` directories are not web-accessible.
Refer to [security warning](https://nette.org/security-warning) for more details.

## Webpack

    `npm run dev` - mode development (rereads js and css on the fly, address is host_ip:3000/assets),   
    `npm run build` - mode production for prepare js and css into www/assets.   

Entry is `resources/js/app.js` and `resources/css/style.css`, all other js files and css files must be imported into them.   

## CSS 
App use [milligram](https://milligram.io) - a minimalist CSS framework.  
