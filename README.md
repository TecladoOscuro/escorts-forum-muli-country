# ForumEscort

Plataforma multi-tenant de foros y directorio de escorts construida con Laravel 13, Livewire 4, Filament 5 y Tailwind CSS 4.

## Stack Tecnologico

| Capa | Tecnologia |
|------|-----------|
| Backend | PHP 8.3+, Laravel 13 |
| Frontend | Blade + Alpine.js (via Livewire), Tailwind CSS 4 |
| Admin | Filament 5.5 |
| Base de datos | SQLite (desarrollo) / MySQL/PostgreSQL (produccion) |
| Build | Vite 8, @tailwindcss/vite |
| Permisos | spatie/laravel-permission |
| Slugs | spatie/laravel-sluggable |

### Por que Blade + Alpine.js y no React/Vue?

La arquitectura deliberadamente evita un framework SPA (React, Vue, etc.) por las siguientes razones:

1. **SEO critico** - El contenido (perfiles, reviews, foros) necesita ser indexado por buscadores. SSR con Blade lo resuelve nativamente sin la complejidad de SSR/hydration de un SPA.
2. **Simplicidad operativa** - No hay API REST separada que mantener, no hay estado duplicado entre cliente y servidor, no hay build de frontend separado complejo.
3. **Rendimiento percibido** - Livewire proporciona interactividad reactiva donde se necesita (componentes dinamicos) y Alpine.js cubre las interacciones ligeras (dropdowns, tabs, toggles) - todo sin los ~80-150KB de bundle de React.
4. **Stack unificado** - Un solo lenguaje (PHP/Blade) para todo el equipo. Menor barrera de entrada para contributors.
5. **Filament** - El panel admin viene "gratis" con Filament, que ya esta integrado con Livewire. Usar React implicaria construir el admin por separado.

Si en el futuro se necesitara una app movil o una experiencia SPA mas rica, se podria exponer una API JSON (Laravel ya lo facilita con API Resources) y construir un frontend React/React Native que la consuma, sin cambiar el backend.

---

## Arquitectura

### Multi-Tenancy

El sistema es **multi-tenant por dominio**. Cada tenant representa un pais/region:

```
forumescort.de  -> Tenant "Deutschland" (locale: de, currency: EUR)
forumescort.es  -> Tenant "Espana"      (locale: es, currency: EUR)
forumescort.uk  -> Tenant "UK"          (locale: en, currency: GBP)
```

El middleware `ResolveTenant` detecta el dominio, carga el tenant correspondiente y configura locale, timezone y currency. Todos los modelos estan scoped por `tenant_id`.

### Modelos de Datos

```
Tenant
 +-- User (role: user | escort | moderator | admin)
 +-- EscortProfile
 |    +-- EscortPhoto
 |    +-- Review
 +-- Category (type: forum | blogs | reviews)
 |    +-- Thread (type: discussion | blog | review)
 |         +-- Post (replies)
 +-- Conversation
 |    +-- Message
 +-- TokenPackage
 +-- TokenTransaction
 +-- Payment
 +-- Report (polimorfismo: reportable_type/id)
 +-- PageView (analytics)
```

### Sistema de Tokens

Modelo de monetizacion basado en tokens:

| Accion | Costo |
|--------|-------|
| Blog visible 30 dias | 80 tokens |
| Featured en homepage 7 dias | 50 tokens |
| Top placement 24h | 20 tokens |

Los tokens se compran mediante paquetes (Starter 100T/15EUR, Standard 250T/35EUR, Premium 600T/70EUR, VIP 1200T/120EUR).

### Internacionalizacion (i18n)

- Archivos JSON en `lang/de.json` y `lang/en.json`
- Todas las vistas usan `__()` para strings traducibles
- Selector de idioma en el header (persistido en session)
- El tenant define el idioma por defecto; el usuario puede sobreescribirlo

---

## Caracteristicas Implementadas

- [x] Verificacion de edad (cookie-based, cumple § 5 JuSchG)
- [x] Autenticacion (login, registro con seleccion de rol)
- [x] Perfiles de escort con galeria, servicios, precios, contacto
- [x] Sistema de reviews con rating de estrellas
- [x] Foro con categorias, threads, replies, pinned, locked
- [x] Blogs de escorts (threads tipo blog vinculados al perfil)
- [x] Mensajeria privada (conversations)
- [x] Busqueda (escorts + forum)
- [x] Sistema de tokens (compra, gasto, historial)
- [x] Multi-tenancy por dominio
- [x] Internacionalizacion DE/EN
- [x] Panel admin (Filament)
- [x] Dark theme con design system basado en CSS custom properties
- [x] Responsive (mobile bottom nav, sidebar colapsable)
- [x] Paginas legales (Impressum, Datenschutz, Forenregeln)
- [x] Analytics basico (page views con IP hash)
- [x] Sistema de reportes (polimorfismo)

---

## Requisitos Legales por Pais

### Alemania (DE) - Implementado

| Ley | Requisito | Estado |
|-----|-----------|--------|
| **JuSchG § 5** | Verificacion de edad obligatoria | Implementado (age-verification gate) |
| **TMG § 5** | Impressum obligatorio | Implementado (pagina /impressum) |
| **DSGVO/GDPR** | Politica de privacidad, derecho a borrado | Implementado (pagina /datenschutz) |
| **NetzDG** | Sistema de reportes, moderacion en 24h | Implementado (modelo Report + status) |
| **ProstSchG** | Las escorts deben tener registro y certificado de salud | Mencionado en reglas del foro |
| **RStV § 55** | Responsable del contenido identificado | En pagina Impressum |
| **UStG § 27a** | Numero de identificacion fiscal | Placeholder en Impressum |

### Espana (ES) - Pendiente

| Ley | Requisito |
|-----|-----------|
| **LSSI-CE** | Aviso legal con datos del titular |
| **LOPD/GDPR** | Politica de privacidad y cookies |
| **Codigo Penal Art. 187** | Prohibido promover prostitucion de menores (verificacion de edad obligatoria) |
| **Ley de Cookies** | Banner de consentimiento de cookies |

### Reino Unido (UK) - Pendiente

| Ley | Requisito |
|-----|-----------|
| **Online Safety Act 2023** | Verificacion de edad robusta para contenido adulto |
| **UK GDPR + DPA 2018** | Politica de privacidad, DPO si aplica |
| **Sexual Offences Act 2003** | Prohibiciones sobre control/explotacion |
| **Advertising Standards (ASA)** | Regulaciones sobre publicidad de servicios para adultos |

### Suiza (CH) - Pendiente

| Ley | Requisito |
|-----|-----------|
| **nDSG (2023)** | Ley de proteccion de datos revisada |
| **Prostitucion legal** | Regulada a nivel cantonal, verificacion de permisos |
| **Verificacion de edad** | Requerida para contenido adulto |

### Austria (AT) - Pendiente

| Ley | Requisito |
|-----|-----------|
| **DSG + GDPR** | Proteccion de datos |
| **Prostitutionsgesetz** | Regulacion varia por Bundesland |
| **ECG (E-Commerce-Gesetz)** | Equivalente al TMG aleman |

---

## Implementacion en Otros Paises

### Pasos para anadir un nuevo pais/tenant

1. **Crear el tenant** en la base de datos:
   ```php
   Tenant::create([
       'name' => 'Espana',
       'slug' => 'es',
       'domain' => 'forumescort.es',
       'locale' => 'es',
       'currency' => 'EUR',
       'timezone' => 'Europe/Madrid',
       'is_active' => true,
   ]);
   ```

2. **Anadir archivo de idioma** `lang/es.json` con las traducciones

3. **Actualizar el middleware `SetLocale`** para incluir `'es'` en los locales permitidos

4. **Crear categorias locales** (ciudades, regiones del pais)

5. **Adaptar paginas legales** segun la legislacion local:
   - Crear variantes de impressum/privacy/rules por tenant, o
   - Usar contenido condicional basado en `$currentTenant->slug`

6. **Configurar dominio** DNS + certificado SSL

7. **Adaptar paquetes de tokens** con precios y moneda local

8. **Seed de datos iniciales** (categorias, paquetes de tokens)

### Consideraciones tecnicas para multi-pais

- **Moneda**: Cada tenant tiene su `currency`. Los precios de tokens se almacenan en centimos (`price_cents`).
- **Timezone**: Configurado por tenant. Las fechas se muestran en la zona horaria del tenant.
- **SEO**: Cada dominio tiene su propio sitemap, meta tags y contenido independiente.
- **Legal**: Las paginas legales deben ser revisadas por un abogado local antes del lanzamiento en cada pais.

---

## Backlog

### Prioridad Alta

- [ ] **Pasarela de pago real** - Integrar Stripe o similar para compra de tokens (actualmente solo placeholder)
- [ ] **Verificacion de escorts** - Flujo de verificacion de identidad (subida de documento + selfie)
- [ ] **Gestion de fotos de escorts** - CRUD completo desde el perfil del escort (subida, reordenar, eliminar)
- [ ] **Notificaciones** - Sistema de notificaciones (nuevo mensaje, nueva review, respuesta en thread)
- [ ] **Recuperacion de contrasena** - Flujo de "olvidaste tu contrasena"
- [ ] **Rate limiting** - Proteccion contra abuso en login, registro, mensajes y reviews
- [ ] **CSRF en age verification** - Validar cookie de verificacion de edad con mas seguridad

### Prioridad Media

- [ ] **Panel de escort** - Dashboard para escorts con estadisticas (vistas, reviews, tokens)
- [ ] **Edicion de perfil** - Formulario para que escorts editen su perfil desde el frontend
- [ ] **Favoritos** - Sistema de bookmarks/favoritos para usuarios
- [ ] **Bloqueo de usuarios** - Permitir bloquear usuarios en mensajeria
- [ ] **Moderacion avanzada** - Panel de moderacion con cola de reportes, acciones masivas
- [ ] **Paginacion de fotos** - Lightbox/galeria con navegacion
- [ ] **Idomas adicionales** - Espanol, Frances, Portugues, Turco
- [ ] **Cache** - Cachear queries frecuentes (escorts featured, stats homepage)
- [ ] **Sitemap XML** - Generacion automatica para SEO

### Prioridad Baja

- [ ] **API REST** - Endpoints JSON para posible app movil futura
- [ ] **PWA** - Service worker para experiencia offline basica
- [ ] **Push notifications** - Web push para notificaciones en tiempo real
- [ ] **2FA** - Autenticacion de dos factores
- [ ] **Social login** - Login con Google/Apple (si aplica al target)
- [ ] **Sistema de badges** - Gamificacion (usuario verificado, top reviewer, etc.)
- [ ] **Estadisticas avanzadas** - Dashboard de analytics con graficas
- [ ] **A/B testing** - Framework para testear variaciones de UI
- [ ] **CDN para imagenes** - Servir fotos desde CDN con resize automatico
- [ ] **Busqueda avanzada** - Filtros por servicio, rango de precio, idioma, etc.
- [ ] **Dark/Light mode** - Toggle de tema (actualmente solo dark)
- [ ] **Tests** - Unit tests, feature tests, browser tests con Dusk

---

## Instalacion

```bash
# Clonar el repositorio
git clone <repo-url>
cd escort-forum

# Instalar dependencias y configurar
composer setup

# O manualmente:
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
npm install
npm run build

# Iniciar desarrollo
composer dev
```

Esto levanta simultaneamente: servidor PHP, cola de trabajos, logs en tiempo real y Vite.

### Acceso por defecto

| Rol | Email | Password |
|-----|-------|----------|
| Admin | admin@forumescort.de | password |
| Escort | sofia@example.com | password |
| Usuario | user1@example.com | password |

---

## Estructura del Proyecto

```
app/
  Http/
    Controllers/     # 11 controllers (Auth, Escort, Forum, Review, etc.)
    Middleware/       # ResolveTenant, VerifyAge, SetLocale
  Models/            # 14 modelos Eloquent
bootstrap/app.php    # Configuracion de middleware
config/app.php       # Locale, timezone, encryption
database/
  migrations/        # Schema completo
  seeders/           # Datos de ejemplo (4 escorts, 8 users, foro, reviews)
lang/
  de.json            # Traducciones aleman
  en.json            # Traducciones ingles
resources/
  views/
    layouts/app.blade.php        # Layout principal
    age-verification.blade.php   # Gate de edad
    home.blade.php               # Homepage
    auth/                        # Login, registro
    escorts/                     # Listado, perfil
    forum/                       # Indice, categoria, thread, crear
    reviews/                     # Listado, crear
    search/                      # Busqueda
    messages/                    # Inbox, conversacion
    tokens/                      # Compra y gasto de tokens
    legal/                       # Impressum, privacidad, reglas
    components/                  # escort-card
  css/app.css                    # Theme con custom properties
  js/app.js                      # Bootstrap (Axios)
routes/web.php                   # Todas las rutas web
```
