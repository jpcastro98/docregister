## Desplegar el proyecto

Para desplegar el proyecto, puedes utilizar cualquier herramienta o servidor en el que puedas instalar los siguientes requisitos:

- PHP: [Instrucciones de instalación](https://www.php.net/manual/en/install.php)
- Composer: [Instrucciones de instalación](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-macos)
- Node.js: [Instrucciones de instalación](https://nodejs.org/en/download)
- npm: [Instrucciones de instalación](https://docs.npmjs.com/downloading-and-installing-node-js-and-npm)

En este caso, utilizare lando para desplegar el proyecto:

### Lando

Lando es una herramienta que nos permite montar entornos de desarrollo local y facilita la configuración y gestión de contenedores Docker, lo que nos permite montar nuestro proyecto en cuestión de minutos.

Para instalar Lando, puedes seguir las indicaciones de la documentación oficial: [Instalación de Lando](https://docs.lando.dev/getting-started/installation.html)

Pasos para desplegar el proyecto:

1. Clonar el proyecto desde el repositorio.
`git clone https://github.com/jpcastro98/docregister.git`
3. Validar la configuración en el archivo `.lando.yml` y asegurarse de que sea correcta. En caso de ser necesario, crear el archivo con la configuración adecuada. Por ejemplo:
```
name: docregister
recipe: laravel
config:
  php: 8.1 
services:
  appserver:
    webroot: public
    xdebug: debug
    config:
      php: .vscode/php.ini
```

 
3. Ejecutar el comando `lando start` para iniciar el contenedor.
4. Ejecutar `lando composer install` para instalar las dependencias del proyecto.
5. Ejecutar el comando `lando info` para obtener las credenciales de la base de datos. Configurar el archivo `.env` con las credenciales correspondientes.

![image](https://github.com/jpcastro98/docregister/assets/121535478/71097e4e-f593-4056-b728-e10d742fde2d)

![image](https://github.com/jpcastro98/docregister/assets/121535478/2dc16444-5491-46ec-966f-d4d3281e9194)

6. Ejecutar `lando artisan migrate` y `lando artisan db:seed` para ejecutar las migraciones de los modelos y registrar los seeders.
7. Ejecutar el comando `npm install` y `npm run build` para instalar las dependencias de Node.js y construir los assets del proyecto.

Estos pasos te permitirán desplegar el proyecto utilizando Lando. 








<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 2000 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Cubet Techno Labs](https://cubettech.com)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[Many](https://www.many.co.uk)**
- **[Webdock, Fast VPS Hosting](https://www.webdock.io/en)**
- **[DevSquad](https://devsquad.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[OP.GG](https://op.gg)**
- **[WebReinvent](https://webreinvent.com/?utm_source=laravel&utm_medium=github&utm_campaign=patreon-sponsors)**
- **[Lendio](https://lendio.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
