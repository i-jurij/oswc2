# Simple CMS project

This is a application built using [Nette](https://nette.org).

## Requirements

This Web Project is compatible with Nette 3.2 and requires PHP 8.1.  

## Installation

You need [Composer](https://getcomposer.org/) and [npm](https://nodejs.org/en/learn/getting-started/an-introduction-to-the-npm-package-manager) or `yarn` or `pnpm`.  

Copy project in your path:   

	git clone https://github.com/i-jurij/oswc2.git
	cd oswc2
	composer install
	npm install
	npm run build

Ensure the `temp/` and `log/` directories are writable.

## Web Server Setup

To quickly dive in, use PHP's built-in server:

	php -S localhost:8000 -t www

Then, open `http://localhost:8000` in your browser to view the welcome page.

For Apache or Nginx users, configure a virtual host pointing to your project's `www/` directory.

**Important Note:** Ensure `app/`, `config/`, `log/`, and `temp/` directories are not web-accessible.
Refer to [security warning](https://nette.org/security-warning) for more details.

## CSS 
App use [bulma](https://bulma.io/documentation/) - a CSS framework.  

## Webpack
    `npm run dev` - mode development (rereads js and css on the fly, address is host_ip:3000/assets),   
    `npm run build` - mode production for prepare js and css into www/assets.   

Entry is `resources/js/app.js` and `resources/css/style.css`, all other js files and css files can be imported into them.  

## Work
### Config   
Configs files are located in "app/config". Read this [documentation](https://doc.nette.org/en/configuring).   
Config `pages_sqlite` is require for page menu on main page and admin pages.  

### Routing
"app/Core/RouterFactory.php", [documentation](https://doc.nette.org/en/application/routing)  
Application use three main routes: "Home", "Sign" and "Admin".   
??? And "Home::Pages::OtherPresenters::method", "Admin::Pages::OtherPresenters::method".   

### Models
`PagesListFacade` - create table pages (for menu at main page).   
