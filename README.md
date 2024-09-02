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
For admins panel app use CSS framework [bootstrap](https://getbootstrap.com/).   
For other pages app use [oswc2_styles](https://github.com/i-jurij/oswc2_styles) - collected from [BassCSS](https://basscss.com/) and [Picnic](https://picnicss.com/) using [Rollup](https://rollupjs.org/) and [rollup-plugin-css-porter](https://www.npmjs.com/package/rollup-plugin-css-porter).  
!!! In BassCSS renamed ".flex" to ".flexx".  
BassCSS using for functionality (typo, margin, padding, display ... ).   
Picnic using for components (buttons, forms, cards, blocks ...).  

## Webpack
    `npm run dev` - mode development (rereads js and css on the fly, address is host_ip:3000/assets),   
    `npm run build` - mode production for prepare js and css into www/assets.   

Entry is `resources/js/app.js` and `resources/css/style.css`, all other js files and css files can be imported into them.  

## Work
### Start
First run console command into project folder:  
```php ./bin/start.php migrate```  
for created db tables "users", "roles", "permissions", "roles_permissions" and "pages". 
It safely for data in existing tables.  
SQL query for tables created is in file "create_sql.php".   

Then run:  
```php ./bin/start.php useradd <username> <password>```  
for user with admin grants creating.  
Password minimal length = 7 (it can be change in app/Model/UserFacade.php).  

Other users can be added from the admin panel.  
After user creating you must create permissions (resource, action) then roles. 
Permissions can be added automatically from classes names of models directory and their methods (you can change it from presenters and theirs methods) or manually.  
If you set in permissions only resource without action - you allow all actions of the resource. 

***If you change columns of table "users" in file "create_sql.php" change it in "app/Model/UsersTableColumns.php" too***

***Users factory can be run from "bin/factorys/user"***  

### Config   
Configs files are located in "app/config". Read this [documentation](https://doc.nette.org/en/configuring).   
Config `pages_sqlite` is require for page menu on main page and admin pages.  

### Routing
"app/Core/RouterFactory.php", [documentation](https://doc.nette.org/en/application/routing)  
Application use three main routes: "Home", "Sign" and "Admin".   
Other routes as in Nette Framework: "Home::Pages::OtherPresenters::method", "Admin::Pages::OtherPresenters::method".   

### Models
`PagesFacade` - get data from table pages (for menu at main page).  
`MyAuthenticator` - get user data (name, roles and other)   
`PermissionFacade` - add, edit, delete permissions   
`UserFacade` - add, edit, delete users   

### Accessory  
`RequireLoggedUser` - trait for page that need autentication (in user not logged - redirect to sign in)  

### Admin page and menu creating
#### Nav menu
For admins basic pages: "Users", "Roles", "Permissions", "Logs", "Cache".  
It is created manually.   

#### Sidebar menu
Menu section CMS is created automatically from filesystem.   
The file structure is as follows eg: 
``` 
CMS dir  
	First dir
	FirstPresenter.php (namespace "App\UI\Cms\First" and class "FirstPresenter")  
			Second dir   
			SecondPresenter.php (namespace "App\UI\Cms\Second" and class "SecondPresenter")  
					Third dir  
					ThirdPresenter.php (namespace "App\UI\Cms\Third" and class "ThirdPresenter")  
```
Menu point will be viewed only if Presenters class existed.				
That is, only controllers classes are used and if you not need the menu point - create method into presenter. And if you need menu links - create dirs and into it create presenters class for action.  
Permissions for user is ["Cms", "menu"].   

## Admins Basic pages

### Admins nav menu
#### Users
Add, edit, delete users and their roles.  

#### Roles
Add, edit, delete roles and permissions for roles.   

#### Permissions
Add, edit, delete Permissions. Permissions can be get from Models or Presenters classes (or both: see `PermissionFacade`) as their methods.   

#### Cache
Clear cache or delete different file(s)   

#### Logs
List, show, clear logs   

## Admins additional pages and sidebar menu
#### CMS
Create, update, delete pages (SEO, content, user permissions etc)
