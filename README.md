# My repository for the course MVC at Blekinge Tekniska Högskola  
![PHP Plushie](/assets/images/phpplush.jpg "A little php plushie")

This is my git repo for the course MVC at Blekinge Tekniska Högskola. It contains the content of my me-page and object oriented php using the symfony framework. 
It also contains my course project adventure game.

## How to clone this repo  

Through your terminal of choice, go to your target directory where you wish to store the files  
(make sure you have git, use **git --version**).  
Type: 
```
git clone https://github.com/PatrikArvius/MVC.git
```

Install dependencies:
```
composer install
npm install
```

## How to run the webpage on your local machine  

Once you have cloned the repository to the directory of your choice, go to said directory through your terminal.  
Then type:
```
php -S localhost:8888 -t public
```
This will start a local server through PHP and you can now go to localhost:8888 in your browser to see the webpage.  

## Scrutinizer badges

[![Build Status](https://scrutinizer-ci.com/g/PatrikArvius/MVC/badges/build.png?b=main)](https://scrutinizer-ci.com/g/PatrikArvius/MVC/build-status/main)
[![Code Coverage](https://scrutinizer-ci.com/g/PatrikArvius/MVC/badges/coverage.png?b=main)](https://scrutinizer-ci.com/g/PatrikArvius/MVC/?branch=main)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/PatrikArvius/MVC/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/PatrikArvius/MVC/?branch=main)