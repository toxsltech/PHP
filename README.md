#### About 
The main objective of this project is to design & develop a social network “Wizz O App” which allows users to filter professionals by choosing their areas and sub-areas of interest. 

### INSTALLATION

- The minimum required PHP version of Yii is PHP 5.4.
- It works best with PHP 7.
- [Follow the Installation Guide](http://192.168.10.22/yii2/wizz-o-app-yii2-1583/blob/master/docs/installation.md)
in order to get step by step instructions.

### Documentation

- A [Definitive Guide](https://www.yiiframework.com/doc/guide/2.0). 

### Directory Structure

```
config/              all modules paths and application configuration 
docs/                documentation
protected/           core framework code
tests/               tests of the core framework code
```

### Language Translation CMD

```
php console.php message/extract @app/config/i18n.php {on root path}
```

### RUN PROJECT

Goto url: http://192.168.10.22/yii2/wizz-o-app-yii2-1583

Create an admin account. I recommend you to use email as admin@toxsl.in and password as admin@123


**In projects**

If you are using Yii 2 base as part of your project there are some important points that you need to takecare throught out of your whole development phase . 

Some UseFull Modules :-
-----------------------

Existing Modules:
-----------------

-Installer

-Logger 

-Blog

-SEO 

-Sitemap

-Backup

-Comment 

-Notification
 
-Payment

-Favorite

-Contact

-Favorite

-Translator

-Portfolio

-Shadow


### CheckList

> NOTE: Refer the [CheckList](http://192.168.10.22/yii2/wizz-o-app-yii2-1583/blob/master/docs/checklist.md) for details on all the security concerns and other important parameters of the project before its actual releasing.

### Coding Guidelines

> NOTE: Refer the [Coding Guidelines](http://192.168.10.22/yii2/wizz-o-app-yii2-1583/blob/master/docs/coding-guidelines.md) for details on all the security concerns and other important parameters of the project before its actual releasing.

### Installation Commands

To install submodules

>bash ../scripts/clone-submodules.sh -l

If you have composer.json

>composer install --prefer-dist

If you need to update vendor again you can use followig command

>composer update --prefer-dist --prefer-stable

To install database run this command

>php console.php installer/install -du=admin -dp=admin@123

Install default data using :

>php console.php clear/defaultReadme
