# Minutes Generator

Módulo Laravel para pegar una transcripción, analizarla con IA y generar una minuta estructurada, editable, aprobable y versionable.

## Stack

- Laravel 13
- MariaDB / MySQL compatible
- Blade + HTML + CSS
- JavaScript vanilla cuando haga falta
- OpenAI Responses API desde backend

## Funcionalidades

- Formulario para cargar transcripciones.
- Validación de longitud mínima y máxima configurable.
- Análisis mediante proveedor IA configurado por `.env`.
- Persistencia de análisis y minutas.
- Vista de minuta generada.
- Edición antes de aprobar.
- Bloqueo de edición y doble aprobación cuando la minuta ya está aprobada.
- Regeneración conservando versiones anteriores.

## Configuración requerida

Copiá `.env.example` a `.env` y configurá:

```env
APP_KEY=
APP_ENV=production
APP_DEBUG=false
APP_URL=https://mg.stglobal.tech

DB_CONNECTION=mariadb
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=

AI_PROVIDER=openai
AI_API_KEY=
AI_MODEL=gpt-4o-mini
AI_MAX_TRANSCRIPT_LENGTH=100000
```

La API key de IA se usa solo en backend. No la pongas en Blade, JavaScript ni variables `VITE_*`.

## Comandos

```bash
composer install
php artisan key:generate
php artisan migrate
php artisan test
```

Este módulo no necesita build frontend para funcionar porque las vistas usan Blade y CSS inline.

## Hostinger

Ver `docs/deployment-hostinger.md`.

Decisión actual: mantener Laravel 13. El hosting confirmado permite PHP 8.3+ y acceso SSH/Git, así que no hace falta bajar la versión del framework.

Importante: no usar PHP 8.2 para este proyecto. `composer.json` requiere PHP `^8.3`.
