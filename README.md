# multiplica
# Colores API

Api para que los demás departamentos y diseñadores puedan tener acceso a los colores.

## Tecnologías utilizadas
* CodeIgniter 3.1.11 (framework PHP)
* Boostrap 5.0.2 (framework CSS)
* Jquery 3.6.0 (herramientas JS)
* PHP 7.4.9
* MySQL 5.7.31
* Apache 2.4.46

## Instalación

Correr los siguientes scripts de SQL en la base de datos:
```sql
CREATE TABLE `ci_sessions` (
   `session_id` varchar(40) NOT NULL DEFAULT '0',
   `ip_address` varchar(45) NOT NULL DEFAULT '0',
   `user_agent` varchar(120) NOT NULL,
   `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
   `user_data` text NOT NULL,
   PRIMARY KEY (`session_id`),
   KEY `last_activity_idx` (`last_activity`)
 ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

CREATE TABLE `color` (
   `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
   `name` varchar(20) DEFAULT NULL,
   `color` varchar(7) DEFAULT NULL,
   `pantone` varchar(10) DEFAULT NULL,
   `year` year(4) DEFAULT NULL,
   PRIMARY KEY (`id`)
 ) ENGINE=MyISAM AUTO_INCREMENT=1002 DEFAULT CHARSET=utf8;

CREATE TABLE `user` (
   `username` varchar(16) NOT NULL,
   `email` varchar(255) DEFAULT NULL,
   `password` varchar(32) NOT NULL,
   `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
   `usertype` enum('A','U') DEFAULT 'U',
   PRIMARY KEY (`username`)
 ) ENGINE=MyISAM DEFAULT CHARSET=utf8;
```
Después, se debe revisar que las conexiones a la base de datos sean los correctos, estos se encuentran en los siguientes archivos:
### API
El archivo se encuentra en **api/colores_class.php**, donde se deben colocar correctamente los parametros solicitados.
```php
private $host = 'localhost';
private $user = 'root';
private $pass = '';
private $dbname = 'multiplica';
```
### Framework (CodeIgniter)
Para colocar los parámetros en el framework, se encuentra en **application/config/database.php**, de preferencia, solo mover los parámetros *-modificable*
```php
$db['default'] = array(
	'dsn'	=> '',
	'hostname' => 'localhost', # -modificable
	'username' => 'root', # -modificable
	'password' => '', # -modificable
	'database' => 'multiplica', # -modificable
	'dbdriver' => 'mysqli',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => (ENVIRONMENT !== 'production'),
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_bin',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);
```
Por ultimo, genera en la tabla de **user** un usuario de tipo *usertype: A* que corresponde al administrador, y otro usuario de tipo *usertype: U*, que corresponde al usuario común (el campo usertype es un enum, donde solo se permite el valor A o U).

## API
El API consta de los siguientes métodos:
#### GET
```batch
curl -X GET [host]/multiplica/api/colores.php
```
Este método trae el listado de todos los colores en la base de datos, se cuenta con los siguientes parámetros opcionales (todos por GET, es decir, en la misma url):
* **type** : Tipo de respuesta, puede ser JSON o XML únicamente, si no se especifica, se regresa JSON.
* **page** : Numero de pagina
* **items** : Cantidad de elementos por pagina
* **id** : ID del color en particular, para este caso, en caso de existir, los parámetros anteriores son omitidos.
#### POST
```batch
curl -X POST [host]/multiplica/api/colores.php -u admin:admin -d "name=&color=&pantone=&year="
```
Este metodo es para insercion de colores en la plataforma, solo puede hacerlos los usuarios de tipo administrador, los campos requeridos son:
* **name** : Nombre del color
* **color** : codigo hexadecimal del color, ej.: #00FF00
* **pantone** : codigo alfanumerico
* **year** : Año
#### PUT / PATCH
```batch
curl -X PUT [host]/multiplica/api/colores.php?id= -u admin:admin -d "name=&color=&pantone=&year="
curl -X PATCH [host]/multiplica/api/colores.php?id= -u admin:admin -d "name=&color=&pantone=&year="
```
Este método es para actualizar un color, solo puede hacerlos los usuarios de tipo administrador, los campos obligatorios son:
* **id** : ID del color a modificar
Los campos opcionales son: 
* name
* color
* pantone
* year
#### DELETE
```batch
curl -X DELETE [host]/multiplica/api/colores.php?id= -u admin:admin
```
Este método sirve para borrar definitivamente un registro de la base de datos, solo puede hacerlos los usuarios de tipo administrador, el parámetro *id* es obligatorio
