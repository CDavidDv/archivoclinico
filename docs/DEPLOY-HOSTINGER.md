# Deploy Hostinger (SSH, sin Node)

Assets Vite ya versionados en `public/build` → no se compila en server.

## 1. Base de datos (hPanel)
Crear MySQL DB + usuario. Anotar: nombre DB, usuario, password, host (normalmente `localhost`).

## 2. Clonar repo (SSH)
Clonar FUERA de `public_html` (ej. carpeta home):
```bash
cd ~
git clone https://github.com/CDavidDv/archivoclinico.git
cd archivoclinico
```

## 3. Dependencias PHP
```bash
composer install --no-dev --optimize-autoloader
```
Si no hay `composer` global: `php composer.phar install --no-dev --optimize-autoloader`

## 4. Configurar .env
```bash
cp .env.example .env
nano .env
```
Editar valores producción (ver plantilla abajo). Luego:
```bash
php artisan key:generate
```

## 5. Migraciones
```bash
php artisan migrate --force
```
Seeders (si necesitas roles/usuario admin inicial):
```bash
php artisan db:seed --force
```

## 6. Storage + permisos
```bash
php artisan storage:link
chmod -R 775 storage bootstrap/cache
```

## 7. Cache producción
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 8. Document root → /public
hPanel → Dominios → cambiar carpeta raíz del dominio a:
`archivoclinico/public`

Si el plan NO deja cambiar document root, alternativa: symlink
```bash
ln -s ~/archivoclinico/public ~/public_html
```
(o copiar contenido de `public/` a `public_html` y ajustar rutas en `index.php`).

## Redeploy (cambios nuevos)
```bash
cd ~/archivoclinico
git pull
composer install --no-dev --optimize-autoloader   # si cambió composer.lock
php artisan migrate --force                        # si hay migraciones nuevas
php artisan config:cache && php artisan route:cache && php artisan view:cache
```
Frontend: build se hace LOCAL, se commitea, y `git pull` lo trae.

---

## Plantilla .env producción
```env
APP_NAME="Archivo Clinico"
APP_ENV=production
APP_KEY=                         # php artisan key:generate lo llena
APP_DEBUG=false
APP_URL=https://TU-DOMINIO.com

APP_LOCALE=es
APP_FALLBACK_LOCALE=es
APP_FAKER_LOCALE=es_ES

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=TU_DB
DB_USERNAME=TU_USER
DB_PASSWORD=TU_PASSWORD

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null
SESSION_SECURE_COOKIE=true

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=public
QUEUE_CONNECTION=database
CACHE_STORE=database

MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=465
MAIL_USERNAME=no-reply@TU-DOMINIO.com
MAIL_PASSWORD=TU_PASSWORD_MAIL
MAIL_SCHEME=smtps
MAIL_FROM_ADDRESS="no-reply@TU-DOMINIO.com"
MAIL_FROM_NAME="${APP_NAME}"

VITE_APP_NAME="${APP_NAME}"
```

### Notas
- `APP_DEBUG=false` obligatorio (seguridad).
- `SESSION_SECURE_COOKIE=true` solo si dominio con HTTPS (Hostinger da SSL gratis).
- Redis omitido → cache/session/queue en database. No requiere extensión Redis.
- `QUEUE_CONNECTION=database`: si NO usas jobs en cola, cambia a `sync`. Si usas, necesitas cron corriendo `queue:work` o Hostinger cron job.
- Mail: config real solo si envías correos (reset password, etc). Si no, deja `MAIL_MAILER=log`.
