# Despliegue en Hostinger

Verificado el 2026-05-10 contra documentación pública de Hostinger y Laravel.

## Decisión de versión

El proyecto está en Laravel 13. `composer.json` permite PHP `^8.3`, pero el lockfile actual incluye dependencias Symfony 8 que requieren PHP `>=8.4`.

Decisión cerrada: mantener Laravel 13.

Motivo: el hosting fue confirmado con PHP 8.2, 8.3, 8.4, 8.5 y acceso SSH/Git. Como el lockfile actual requiere PHP 8.4+, el sitio debe configurarse con PHP 8.4 o 8.5. No usar PHP 8.2 ni 8.3 para este build.

Mantener Laravel 13 es correcto porque el plan de Hostinger permite:

- PHP 8.4 o superior para este lockfile.
- SSH o Git deployment.
- Ejecutar `composer install`.
- Ejecutar `php artisan migrate`.

Tradeoff: Laravel 13 da más vida útil y soporte moderno, pero puede no coincidir con el auto-instalador de Hostinger. La documentación de Hostinger para auto-instalación todavía menciona Laravel 10.x, así que NO hay que asumir que el auto-installer sirve para este proyecto.

Si otro entorno solo permitiera instalar Laravel desde auto-installer sin SSH/Git, ahí sí convendría recrear el proyecto en una versión disponible. No aplica al hosting confirmado.

## Variables de producción

Usá `.env.production.example` como guía:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://mg.stglobal.tech

DB_CONNECTION=mariadb
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=nombre_real
DB_USERNAME=usuario_real
DB_PASSWORD=password_real

AI_PROVIDER=nvidia
AI_API_KEY=clave_real
AI_MODEL=meta/llama-3.1-70b-instruct
AI_BASE_URL=https://integrate.api.nvidia.com/v1
AI_MAX_TRANSCRIPT_LENGTH=20000
```

## Checklist

1. En hPanel, seleccionar PHP 8.4 o 8.5 para el sitio.
2. Crear base de datos MySQL/MariaDB y guardar nombre, usuario, host y password.
3. Subir o desplegar el proyecto.
4. Copiar `.env.production.example` a `.env`.
5. Confirmar `APP_URL=https://mg.stglobal.tech`, generar `APP_KEY`, y completar credenciales DB y `AI_API_KEY`.
6. Ejecutar:

```bash
composer install --no-dev --optimize-autoloader
php artisan key:generate --force
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

7. Apuntar el document root del dominio a `public/`.
8. Verificar `/`.

## Fuentes

- Hostinger — versiones Laravel soportadas por auto-installer: https://support.hostinger.com/en/articles/1583301-which-laravel-versions-are-supported-at-hostinger
- Hostinger — scripts faltantes/outdated e instalación por SSH: https://support.hostinger.com/en/articles/1583391-missing-or-outdated-scripts-on-auto-installer
- Hostinger — Git deployment: https://support.hostinger.com/en/articles/1583302-how-to-deploy-a-git-repository-in-hostinger
- Hostinger — crear base de datos MySQL: https://support.hostinger.com/en/articles/1583542-how-to-create-a-new-mysql-database
- Laravel — soporte de versiones: https://laravel.com/docs/13.x/releases

## CI/CD con GitHub Actions + `mg-cd-ci`

El despliegue queda igual al patrón de `STGlobalApp`: el repo fuente construye un artefacto y lo publica en un repo independiente de despliegue, `STGloba1/mg-cd-ci`.

### Branch de despliegue

El workflow publica el artefacto Laravel en:

```txt
STGloba1/mg-cd-ci → branch main
```

En Hostinger, Git Deployment debe apuntar a ese repo y al branch `main`. El document root del dominio debe apuntar a `public/` dentro del artefacto desplegado.

### Secret requerido en GitHub

Crear en el repo fuente → Settings → Secrets and variables → Actions:

```txt
HOSTING_REPO_TOKEN=<token con permisos de push sobre STGloba1/mg-cd-ci>
```

### Flujo configurado

`.github/workflows/ci-cd.yml` hace:

1. En pull requests a `main`: instala dependencias, prepara SQLite de testing, corre tests y ejecuta `npm run build`.
2. En push a `main`: repite tests/build, instala dependencias PHP de producción y prepara el artefacto.
3. Publica el artefacto en `mg-cd-ci`, branch `main`, con commit convencional `chore(deploy): ...`.
4. Hostinger despliega desde `mg-cd-ci`, no desde el repo fuente.

### Artefacto Laravel

`scripts/prepare-hostinger-laravel-artifact.sh` copia lo necesario para producción, incluyendo:

- `app/`, `bootstrap/`, `config/`, `database/`, `public/`, `resources/`, `routes/`, `storage/`, `vendor/`
- assets compilados en `public/build`, si existen

Y excluye:

- `.env` y `.env.*`
- `.github/`
- `node_modules/`
- `tests/`
- logs locales

La `.env` real se mantiene solo en Hostinger.
