# Libros — libros.javired.com

Tienda de eBooks con WordPress + WooCommerce.

## Deploy Automático

Cada push a `main` despliega automáticamente al hosting vía GitHub Actions (FTPS + SSH).

### Archivos de Deploy
- `.github/workflows/deploy.yml` — Pipeline CI/CD
- `wp-config-deploy.php` — Template de configuración (los secrets se inyectan en deploy)
- `database/` — Dumps SQL para migraciones

### Secretos Requeridos (GitHub Settings → Secrets)
| Secret | Descripción |
|--------|------------|
| `FTP_SERVER` | Host FTP |
| `FTP_USERNAME` | Usuario FTP |
| `FTP_PASSWORD` | Contraseña FTP |
| `SSH_HOST` / `SSH_USER` / `SSH_PASSWORD` / `SSH_PORT` | Acceso SSH |
| `REMOTE_PATH` | Ruta en el servidor |
| `DB_NAME` / `DB_USER` / `DB_PASSWORD` / `DB_HOST` | Credenciales MySQL |
