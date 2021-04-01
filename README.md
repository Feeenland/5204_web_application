# 5204 PHP Web application WDD19 Laura Lea Müller ![paw](web/img/favicon/favicon-32x32.png)
SAE Institute Zürich, Submission date Apr, 29. 2021
***

## For the teacher who reviews and evaluates the project
**Database**

_The file to create the Database is in this folder:_

    Database:
    5204_php / database / config / 5204_web_application.sql

**Login**

_These are the data to log into my website in the backend as a user:_

    Login:
    nickname: teacher PW: oop5204

**Files**

_in every file at the top you find a description what this file does_

**Folders**

_to understand the folder structure please read the section Folder structure in the README.md_
***
## Database 
![DB structure](img/readme/db.png)
***

## Overview ![paw](web/img/favicon/favicon-16x16.png)
**CMS Project Leder Tatze**

For this school project i had to program a cms by myself.
I have decided to revise and add to my existing "Leder LARP Tatze" page.
The goal is a simple CMS where you can delete, edit and add things.

The page changes from Leder LARP Tatze to Leder Tatze this makes the whole thing a bit more general. 
Also, the site is now being set up with Bootstrap and Mobile first.
***

## Project structure ![paw](web/img/favicon/favicon-16x16.png)  
**PHP**  
my php code is unfortunately a mishmash between object-oriented programming and not object-oriented programming. 
this for the reason that I can not yet determine the difference everywhere and thus also could not locate with examples 
from the internet whether this is object-oriented or not.

Every file that outputs HTML is in the folder `templates`.  
Every file that does something in the Database is in the folder `modules`.  
Every file that controls something is in the folder `controllers`.
***

## Installation and system ![paw](web/img/favicon/favicon-16x16.png)  
**In this project the following languages and libraries were used:**
 * PHP
 * js and jquery
 * css/ scss
 * HTML5
 * gulp
 * Bootstrap
 * git
 
To edit or change the project open gitBash or any other console in the root folder.

In case you haven't already installed npm and node.js globally, please do that first. [link to install npm via node.js ](https://www.npmjs.com/get-npm)  
   if you are not sure about it run `node -v` and `npm -v` this will tell you which version you have installed, or otherwise if it is not available.

Run `npm install` this command will run the package.json file and thus install gulp and Bootstrap.  
It will create a folder named node_modules.

Because scss is used here, a watcher must be activated. Run `gulp watch`  
 this will now automatically open a browser with a live server of the project.
this command will run the gulpfile.js file and inside it the watch function.

What it actually does is
* it watches for changes in the scss files, takes the scss files, changes those to css add autoprefix an saves them in the css folder
* it takes the created css in the css folder and uglify the file , before it saves it in the min_css folder
* it watches for changes in the html files and updates the live server on it.
* it watches for changes in the js files and updates the live server on it.

Now you can work on this project.
The whole thing is actually just for templateing.
With PHP the whole thing gets a bit more complicated, That's why I did the whole templating with style before I started with PHP. and then only added minor changes.
***

## Folder structure ![paw](web/img/favicon/favicon-16x16.png)
**To be honest, that's probably what I spent most of my time with.
  Since I had no idea how to build such a project correctly, beautifully and understandably.
  I hope what I've done now is understandable.**  

    `5100_php_website_laura_lea_mueller_WDD919` = is the root folder.
     1. `config` = contains files for configuration like the database.
     2. `node_modules` = contains files and folders from the node.
     3. `web` = contains everything for the website.
        3.1. `controllers` = contains all the files which controls something.
        3.2. `css` = finished css files
        3.4. `database` = contains the connection files for the DB.
        3.5. `fonts` = contains all the used fonts.
        3.6. `helpers` = contains helper files.
        3.7. `img` = contains all images.
        3.8. `js` = contains .js files.
        3.9. `min_css` = contains the min css file.
        3.10. `models` = contains files which does something in the Database.
        3.11. `scss` = contains all .scss files.
        3.12. `templates` = contains every file that outputs some HTML.
     
     4. .gitignore = tells git which files or folders it should ignore.
     5. .htacces = removes the .php ending in the URL.
     6. gulpfile.js = it contains the functions and instructions for gulp.
     7. package.json = contains instructions for node.
     8. package-lock.json = auto generated file from node.
     9. README.md = contains everything that is important to work on this project.
     10. README.pdf = contains the README.md as a PDF.
 
***

## source directory ![paw](web/img/favicon/favicon-16x16.png)
**Pictures**  
Nearly all of the Pictures I have used in this project are edited by myself.

only 3 are not, i got the form:
* [pinterest.com](https://www.pinterest.de/pin/535295105706622703/) 

