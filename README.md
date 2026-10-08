# AFDeveloper — sitio + panel administrativo

Portafolio público de [afdeveloper.com](https://afdeveloper.com) y panel privado para gestionar
contenido y finanzas del negocio.

**Stack:** Laravel 13 · Filament 5 (Livewire 4) · PHP 8.3+ · MySQL/MariaDB · Docker (Coolify)

---

## Qué cambió respecto a la versión anterior (Laravel 8)

### Seguridad (lo urgente)
| Antes | Ahora |
|---|---|
| `/bandeja` era pública: cualquiera veía y borraba los mensajes de contacto | La bandeja vive dentro del panel, detrás de login |
| `POST /api/register` abierto: cualquiera se creaba un usuario y entraba al admin | API eliminada. Acceso al panel limitado por `ADMIN_EMAILS` |
| Los errores mostraban el stack trace al usuario | Mensajes genéricos + páginas de error propias |
| `/test-summernote` y la plantilla `public/melody` (34 MB, con un `debug.log` expuesto) | Eliminados |
| Formulario de contacto sin protección | Honeypot + límite de 5 envíos/minuto por IP |

### Rendimiento
- Se eliminaron 3 service providers que consultaban la BD en **cada** request (incluso en `artisan`).
  Ahora los datos de la empresa se cachean y solo se cargan en las vistas públicas.
- Los correos de contacto se envían en cola: el visitante no espera al SMTP.
- jQuery fuera del sitio público (se cargaba dos veces). El formulario usa `fetch`.

### Finanzas (rediseño completo)
- **Una sola tabla `transactions`** para ingresos y gastos (antes eran dos tablas con el código copiado).
- **Categorías** con color, **cuentas** (banco, Nequi, efectivo, tarjeta) con saldo calculado,
  **clientes** que pagan, vínculo a **proyectos**.
- **Pendientes**: cuentas por cobrar y por pagar con fecha límite y alerta de vencidos.
- **Multimoneda**: COP / USD / EUR con tasa de cambio; todo se consolida en la moneda base.
  Al registrar un pago en dólares se pide la tasa real de liquidación.
- **Recurrentes**: VPS, dominios, igualas… se generan solos cada mañana como pendientes.
  Si el servidor estuvo caído, se ponen al día sin duplicar.
- **Soportes**: adjunta la factura o comprobante (se guarda en disco privado, no público).
- **Dashboard** con filtro de periodo: ingresos/gastos vs periodo anterior, utilidad y margen,
  por cobrar, flujo de caja de 12 meses, gastos por categoría y próximos vencimientos.
- **Exportar a CSV** (compatible con Excel) respetando los filtros aplicados.
- Montos hasta billones (`decimal(15,2)`; antes el tope era ~99 millones).

### Contenido y sitio público
- Panel Filament para proyectos, blog, testimonios, equipo, bandeja y configuración del sitio.
- **Blog de verdad**: cada artículo tiene su URL (`/blog/mi-articulo`), meta descripción, Open Graph,
  datos estructurados (JSON-LD), tiempo de lectura y publicación programada. Antes estaba roto
  (usaba columnas que no existían).
- Proyectos con stack tecnológico, repositorio, destacados y orden por arrastre.
- `sitemap.xml`, URL canónica y redirecciones 301 desde las URLs viejas (`/proyects`, `/blogs`, `/clientes`).
- Redes sociales y WhatsApp configurables desde el panel (antes salían con `href="#"`).
- Mensajes de contacto: se pueden marcar como leídos, responder por correo o WhatsApp.

---

## Integraciones

### WhatsApp (WAHA)
Con `WAHA_ENABLED=true` y `ADMIN_WHATSAPP=57…`, te llega un WhatsApp por cada mensaje del formulario
de contacto. Usa el mismo servidor WAHA que CrediTrack (ver su README).

### CrediTrack
`POST /webhooks/creditrack` recibe eventos firmados (HMAC-SHA256, máx. 5 min de antigüedad):
- desembolso → gasto en "Préstamos: desembolsos";
- pago → ingreso en "Préstamos: recaudos";
- pago o préstamo borrado → movimiento a la papelera.

Es idempotente: los reintentos no duplican. Configura `CREDITRACK_WEBHOOK_SECRET` con el valor que
genera `php artisan creditrack:webhook` en CrediTrack.

## Desarrollo local

```bash
composer install
cp .env.example .env && php artisan key:generate
# configura DB_* en .env
php artisan migrate
php artisan db:seed            # categorías iniciales (opcional)
php artisan make:filament-user # si no tienes usuario
php artisan serve              # http://localhost:8000  ·  panel en /admin
```

En otra terminal, para que salgan los correos y corran los recurrentes:

```bash
php artisan queue:work
php artisan schedule:work
```

Tests:

```bash
php artisan test
```

---

## Actualizar producción (desde la versión Laravel 8)

> ⚠️ **Haz un backup de la base de datos y de `public/images` antes de empezar.**

1. **Backup**: `mysqldump -u USER -p BASE > backup-$(date +%F).sql`
2. Sube el código a una rama nueva (`git checkout -b v2`), corre `composer update` en local
   y **commitea el `composer.lock`** generado.
3. En Coolify, agrega o revisa estas **variables de entorno**:
   ```
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://afdeveloper.com
   APP_TIMEZONE=America/Bogota
   ADMIN_EMAILS=alancarabali@gmail.com
   CONTACT_EMAIL=alancarabali@gmail.com
   SESSION_DRIVER=database
   CACHE_STORE=database
   QUEUE_CONNECTION=database
   MAIL_MAILER=smtp   # + MAIL_HOST, MAIL_PORT, MAIL_USERNAME, MAIL_PASSWORD, MAIL_FROM_ADDRESS
   ```
4. **Volúmenes persistentes** en Coolify (Storages), para no perder imágenes en cada despliegue:
   - `/app/public/images`
   - `/app/public/image`
   - `/app/storage/app`
5. Despliega. Al arrancar, el contenedor ejecuta las migraciones solo
   (`docker/entrypoint-laravel.sh`), que:
   - renombran `links → projects`, `clients → testimonials`, `bandejas → contact_messages`;
   - agregan slugs, destacados, SEO y campos nuevos;
   - crean las tablas de finanzas y **copian** tus ingresos y gastos actuales.
6. Opcional: `php artisan db:seed --force` para las categorías sugeridas.
7. Entra a `/admin` con tu usuario de siempre (las contraseñas no cambian).
8. Revisa **Configuración** (redes, WhatsApp, SEO) y asigna categorías a los movimientos migrados.

Las tablas `incomes` y `expenses` **no se borran**: quedan como respaldo. Cuando verifiques que los
totales cuadran, se pueden eliminar con una migración.

### Si algo sale mal
Restaura el backup y vuelve a desplegar la rama anterior. Las migraciones nuevas también tienen `down()`.

---

## Estructura

```
app/
├── Enums/                  Tipos y estados (TransactionType, Currency, Frequency…)
├── Filament/
│   ├── Pages/              Dashboard con filtros, Configuración del sitio
│   ├── Resources/          Movimientos, Clientes, Recurrentes, Cuentas, Categorías,
│   │                       Proyectos, Blog, Testimonios, Equipo, Bandeja
│   └── Widgets/            Resumen, flujo de caja, gastos por categoría, pendientes
├── Http/Controllers/       Sitio público, blog, contacto, sitemap
├── Models/                 Eloquent (Transaction es el corazón de finanzas)
└── Console/Commands/       finance:recurring (programado a las 6:00 a. m.)
docker/                     Supervisor (cola + scheduler) y entrypoint
```
