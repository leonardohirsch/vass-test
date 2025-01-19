# Rick and Morty Characters Plugin

Este plugin para WordPress permite a los usuarios importar y administrar personajes de la API de Rick and Morty. Ofrece funcionalidades como importación manual de personajes, búsqueda y filtrado en el frontend.

## Características

- **Tipo de Post Personalizado**: Define un tipo de post personalizado (CPT) para los personajes.
- **Taxonomías Personalizadas**: Clasifica los personajes por especies.
- **Importación Manual**: Permite a los usuarios importar personajes directamente desde la API de Rick and Morty mediante un botón en el panel de administración.
- **Búsqueda y Filtrado AJAX**: Los usuarios pueden buscar y filtrar personajes en el frontend sin recargar la página.
- **Shortcode Dinámico**: Incluye un shortcode `[rick_morty_characters]` que renderiza la búsqueda y el listado de los personajes.

## Instalación

1. Clona el repositorio en tu directorio de plugins de WordPress
2. Navega al área de administración de WordPress y activa el plugin desde el menú 'Plugins'.

## Uso

### Shortcode

Inserta el shortcode `[rick_morty_characters]` en cualquier página o entrada para mostrar el formulario de búsqueda y la lista de personajes importados.

### Importación de Personajes

Ve a `Rick and Morty API -> Import Characters` en el panel de administración y haz clic en `Import Characters` para comenzar la importación de personajes desde la API de Rick and Morty.

### Configuraciones

Configura el plugin bajo `Rick and Morty -> Import Settings`:

- **Characters API Endpoint URL**: Se puede establecer el endpoint de la API para la importación de personajes, en caso de que cambiara en el futuro.
- **Cache Expiry Time**: Configura el tiempo de expiración del cache (en segundos) que computa el total de personajes descargados.

# Desarrollo

### Requerimientos de Desarrollo

- **PHP**: Versión 8.0 o superior.
- **WordPress**: Versión 6.0 o superior.
- **MySQL**: Versión 8.

## Entorno de Desarrollo con Docker

Para el ambiente de desarrollo se usó **Docker**. A continuación, se proporciona la configuración de **Docker Compose** usada.

### Configuración de Docker Compose

La configuración del archivo `docker-compose.yml` que se muestra a continuación configura los siguientes servicios:

- **MySQL**: Base de datos MySQL versión 8.
- **WordPress**: Última versión de WordPress conectada a la base de datos MySQL.
- **phpMyAdmin**: Una interfaz de administración de base de datos MySQL accesible desde el navegador.

```yaml
version: "3.8"
services:
  db:
    container_name: "vass_mysql"
    image: mysql:8
    volumes:
      - db_data:/var/lib/mysql
    restart: always
    environment:
      MYSQL_ROOT_PASSWORD: password
      MYSQL_DATABASE: wordpress
      MYSQL_USER: wordpress
      MYSQL_PASSWORD: wordpress
    ports:
      - "3316:3306"

  wordpress:
    container_name: "vass_wordpress"
    depends_on:
      - db
    image: wordpress:latest
    ports:
      - "8000:80"
    restart: always
    environment:
      WORDPRESS_DB_HOST: db:3306
      WORDPRESS_DB_USER: wordpress
      WORDPRESS_DB_PASSWORD: wordpress
      WORDPRESS_DB_NAME: wordpress
    volumes:
      - ./wordpress:/var/www/html/wp-content/
      - ./logs/:/var/log/

  phpmyadmin:
    depends_on:
      - db
    image: phpmyadmin/phpmyadmin:latest
    container_name: phpmyadmin
    restart: always
    ports:
      - 8180:80
    environment:
      PMA_HOST: db
      MYSQL_ROOT_PASSWORD: password

volumes:
  db_data:
```

### Uso del Entorno de Docker

Para iniciar el entorno de desarrollo:

- Tener Docker y Docker Compose instalados en el sistema.
- Navega al directorio del proyecto, crear el archivo `docker-compose.yml`, ejecutar en la terminar el comando `docker-compose up -d`.

## Configuración de Composer

Este proyecto utiliza **Composer** para configurar el autoloading de clases PHP. Para elo, se debe ejecutar los siguientes comandos en la terminal:

```bash

composer install
composer dump-autoload
```

La configuración de composer para el proyecto se puede editar en el archivo `composer.json`
