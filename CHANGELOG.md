# Changelog

Todos los cambios importantes de este proyecto se registran en este archivo.
El formato sigue Keep a Changelog y el proyecto usa versionado semántico.

## [Unreleased]
### Added
- GET /api/v1/health
- Errores en JSON para rutas /api

### Added
- Login con token de Sanctum (POST /api/v1/auth/login)
- Perfil del usuario autenticado (GET /api/v1/auth/me)
- Cierre de sesión que revoca el token (POST /api/v1/auth/logout)
- Bloqueo de usuarios inactivos y límite de 5 intentos de login por minuto
- Esquema Bearer en la documentación

### Changed
- El endpoint de salud pasa a un controlador invocable