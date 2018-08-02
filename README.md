#Control de Calidad para SIBA STD - Backend

App que expone un API basado en REST para la aplicación de control de calidad de SIBA STD

Base structure to start an app based on Laravel 5.4 running on a docker container (https://hub.docker.com/r/maomuriel/lara54-php70/)

Comandos Docker para iniciar:

1. Lanza el contenedor de la DB

La imagen de este contenedor: https://hub.docker.com/_/mariadb/ (docker pull mariadb:10.2)

docker run --name std-qa-back-mariadb -v [/my/own/datadir]:/var/lib/mysql --network std-qa-back -e MYSQL_ROOT_PASSWORD=[DB-PWD] -d mariadb:10.2

2. Lanza el contendor de la App

La imagen de este contenedor: https://hub.docker.com/_/mariadb/ (docker pull mariadb:10.2)

docker run -d --name std-qa-back -v [/path/to/app]:/home/admin/app -p 9000:80 --network std-qa-back --link std-qa-back-mariadb:mysql  maomuriel/lara54-php70:v0.1.1 (si esta imagen falla, utilizar el tag: latest, la imagen con el tag v0.1.1 solo difiere en la definción de zona horaria a America/Bogota)

3. Lanza contenedor phpMyAdmin

La imagen de este contenedor: https://hub.docker.com/r/phpmyadmin/phpmyadmin/

docker run --name std-qa-myadmin -d --network std-qa-back -e PMA_HOST='std-qa-back-mariadb' -p 9090:80 phpmyadmin/phpmyadmin