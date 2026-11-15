# TaskFlow API

[![tests](https://github.com/USUARIO/taskflow-api/actions/workflows/tests.yml/badge.svg)](https://github.com/USUARIO/taskflow-api/actions/workflows/tests.yml)
![PHP](https://img.shields.io/badge/PHP-8.3-777BB4)
![Laravel](https://img.shields.io/badge/Laravel-13-FF2D20)
![Licencia](https://img.shields.io/badge/licencia-MIT-blue)

API REST para gestionar proyectos, tareas y colaboradores.
Proyecto guiado del programa ADSO (SENA), ficha 3174404.

- Producción: https://3174404-07.rdsdevs.com.co/api/v1
- Versión actual: v0.1.0

## Contenido
- [Tecnologías](#tecnologías)
- [Instalación](#instalación)
- [Pruebas](#pruebas)
- [Endpoints](#endpoints)
- [Flujo de trabajo](#flujo-de-trabajo)
- [Licencia](#licencia)

## Tecnologías
PHP 8.3, Laravel 13, MySQL 8, Laravel Sanctum, Pest, Scramble.

## Requisitos
PHP 8.3+, Composer 2, MySQL 8, Git.

## Instalación
```bash
git clone git@github.com:USUARIO/taskflow-api.git
cd taskflow-api
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```
## Modelo de datos

Diagrama entidad-relación: [docs/er.md](docs/er.md)

## Datos de prueba

```bash
php artisan migrate:fresh --seed
```

Crea un administrador (admin@taskflow.test / password), 8 colaboradores,
4 proyectos y 24 tareas. Solo para desarrollo local.

## Variables de entorno
| Variable    | Ejemplo        | Descripción             |
|-------------|----------------|-------------------------|
| DB_DATABASE | db_taskflow_api       | Base de datos MySQL     |
| DB_PREFIX   | a07_           | Prefijo de las tablas   |

## Pruebas
```bash
php artisan test
```

## Documentación de la API
Con el servidor local: http://127.0.0.1:8000/docs/api

## Endpoints

| Método | Ruta                 | Descripción                       | Auth   |
|--------|----------------------|-----------------------------------|--------|
| GET    | /api/v1/health       | Estado del servicio               | No     |
| POST   | /api/v1/auth/login   | Inicia sesión y entrega un token  | No     |
| GET    | /api/v1/auth/me      | Perfil del usuario autenticado    | Bearer |
| POST   | /api/v1/auth/logout  | Cierra sesión y revoca el token   | Bearer |

## Autenticación

1. POST /api/v1/auth/login con email, password y device_name.
2. Guardar el token de la respuesta.
3. Enviar en cada petición protegida:
   Authorization: Bearer {token}
   Accept: application/json

El login admite 5 intentos por minuto por correo e IP.

## Flujo de trabajo
Git Flow (main, develop, feature/*, release/*, hotfix/*),
Conventional Commits y versionado semántico.
Los cambios se registran en CHANGELOG.md.

## Autor
Ana Pérez · aprendiz ADSO · github.com/USUARIO

## Licencia
Distribuido bajo la licencia MIT. Ver LICENSE.md.