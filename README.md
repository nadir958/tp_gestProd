# Symfony TP Technomaker 2020

## Start a new Symfony project

Build and launch containers
```
docker-compose up -d
```

Access to php container as dev user
```
docker exec -it -u dev sf4_php bash
```

So let’s go to your home :
```
cd /home/wwwroot/sf4
```

Installation of Symfony with composer
```
composer create-project symfony/website-skeleton:^4.4 temp
```

When it’s done, we will get the project to the root path.
```
cp -Rf /home/wwwroot/sf4/temp/. .
rm -Rf /home/wwwroot/sf4/temp
```

Launch in your browser : http://localhost/


## install an existing Symfony project

Build and launch containers
```
docker-compose up -d
```

Access to php container as dev user
```
docker exec -it -u dev sf4_php bash
```

Go to your project folder
```
cd /home/wwwroot/sf4
```

Install Composer dependencies 
```
composer install
```

Install npm packages 
```
npm install
```

Compile assets
```
npm run dev
```

Exit from container
```
exit
```

Launch in your browser : http://localhost/


## Usage

Starting the application
```
docker-compose up -d
```

Stopping the Application
```
docker-compose stop
```

If you want to launch bin/console commands from local
```
docker-compose exec php sf4/bin/console
```

To recompile assets automatically when files change use
```
npm run watch
```

* Symfony app: `http://localhost/`
* phpMyAdmin: `http://localhost:8080/`
* Access to the DB: `jdbc:mysql://localhost:3306/sf4` (user/pass: sf4)
