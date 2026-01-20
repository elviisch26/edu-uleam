# Sistema de Gestion de Tareas Academicas

**Proyecto de Tecnologias Emergentes**

Sistema web desarrollado con Laravel 12 para la gestion de tareas academicas, permitiendo a docentes crear y calificar tareas, y a estudiantes entregarlas y recibir retroalimentacion.

## Descripcion del Proyecto

Este proyecto fue desarrollado como parte de la asignatura de Tecnologias Emergentes. Implementa un sistema completo de gestion de tareas academicas con las siguientes caracteristicas principales:

- Sistema de autenticacion y autorizacion basado en roles (Admin/Docente/Estudiante)
- Gestion completa de tareas (CRUD) con Soft Deletes
- Sistema de entregas de tareas con carga de archivos
- Sistema de calificaciones y retroalimentacion
- Interfaz responsiva con Tailwind CSS
- Editor de texto enriquecido con Trix
- Arquitectura basada en Services, Policies y Form Requests
- Suite de pruebas automatizadas con PHPUnit

## Caracteristicas Principales

### Para Docentes
- Crear, editar y eliminar tareas (con eliminacion logica)
- Adjuntar archivos guia a las tareas
- Visualizar todas las entregas de sus estudiantes
- Calificar entregas y proporcionar retroalimentacion
- Descargar archivos entregados por estudiantes
- Editar calificaciones previamente asignadas

### Para Estudiantes
- Ver todas las tareas disponibles
- Entregar tareas con archivos adjuntos
- Ver estado de sus entregas (dias restantes, estado de vencimiento)
- Consultar calificaciones y retroalimentacion recibida
- Acceso a archivos guia proporcionados por docentes

## Arquitectura del Proyecto

El proyecto implementa una arquitectura limpia siguiendo las mejores practicas de Laravel:

```
app/
├── Enums/
│   └── RolEnum.php              # Enumeracion de roles del sistema
├── Http/
│   ├── Controllers/
│   │   ├── DashboardController.php   # Controlador dedicado para dashboard
│   │   ├── TareaController.php       # Gestion de tareas
│   │   ├── EntregaController.php     # Gestion de entregas
│   │   └── CalificacionController.php # Gestion de calificaciones
│   ├── Requests/
│   │   ├── StoreTareaRequest.php     # Validacion crear tarea
│   │   ├── UpdateTareaRequest.php    # Validacion actualizar tarea
│   │   ├── StoreEntregaRequest.php   # Validacion crear entrega
│   │   └── Store/UpdateCalificacionRequest.php
│   └── Middleware/
│       └── CheckRole.php             # Middleware de verificacion de rol
├── Models/
│   ├── User.php                 # Con relaciones y factory states
│   ├── Tarea.php                # Con SoftDeletes, accessors y scopes
│   ├── Entrega.php              # Con SoftDeletes y accessors
│   ├── Calificacion.php         # Con SoftDeletes y accessors
│   └── Rol.php                  # Modelo de roles
├── Policies/
│   ├── TareaPolicy.php          # Autorizacion para tareas
│   ├── EntregaPolicy.php        # Autorizacion para entregas
│   └── CalificacionPolicy.php   # Autorizacion para calificaciones
├── Services/
│   ├── TareaService.php         # Logica de negocio de tareas
│   ├── EntregaService.php       # Logica de negocio de entregas
│   └── CalificacionService.php  # Logica de negocio de calificaciones
└── Traits/
    └── HasFiles.php             # Trait reutilizable para manejo de archivos
```

## Tecnologias Utilizadas

### Backend
- **PHP** 8.2+
- **Laravel** 12.0
- **Laravel Breeze** - Sistema de autenticacion
- **SQLite/MySQL/PostgreSQL** - Base de datos

### Frontend
- **Blade Templates** - Motor de plantillas
- **Tailwind CSS** 3.x - Framework CSS
- **Alpine.js** - Framework JavaScript
- **Trix Editor** - Editor de texto enriquecido
- **Vite** - Build tool

### Testing
- **PHPUnit** - Framework de pruebas
- **Laravel Testing** - Helpers de testing
- **47 tests** con 104 aserciones

## Requisitos del Sistema

- PHP >= 8.2
- Composer
- Node.js >= 18.x
- NPM o Yarn
- SQLite 3 / MySQL >= 8.0 / PostgreSQL >= 13
- Extensiones PHP: OpenSSL, PDO, Mbstring, Tokenizer, XML, Ctype, JSON, BCMath, Fileinfo

## Instalacion

### 1. Clonar el repositorio

```bash
git clone https://github.com/elviisch26/edu-uleam.git
cd edu-uleam
```

### 2. Instalar dependencias

```bash
composer install
npm install
```

### 3. Configurar variables de entorno

```bash
cp .env.example .env
```

Para SQLite (recomendado para desarrollo):
```env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

Para MySQL:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=edu_uleam
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
```

### 4. Generar key y preparar base de datos

```bash
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
```

### 5. Compilar assets

```bash
# Desarrollo
npm run dev

# Produccion
npm run build
```

### 6. Iniciar el servidor

```bash
php artisan serve
```

La aplicacion estara disponible en `http://localhost:8000`

## Estructura de la Base de Datos

### Tablas Principales

| Tabla | Descripcion |
|-------|-------------|
| usuarios | Usuarios del sistema con rol asignado |
| roles | Roles disponibles (admin, docente, estudiante) |
| tareas | Tareas creadas por docentes |
| entregas | Entregas realizadas por estudiantes |
| calificaciones | Calificaciones asignadas a entregas |

### Caracteristicas de la Base de Datos
- **Soft Deletes** en tareas, entregas y calificaciones
- **Indices** optimizados para consultas frecuentes
- **Restricciones de integridad** referencial
- **Timestamps** automaticos

## Configuracion de Roles

El sistema maneja tres roles principales:

| Rol | ID | Permisos |
|-----|-----|----------|
| admin | 1 | Acceso completo al sistema |
| docente | 2 | Crear tareas, ver entregas, calificar |
| estudiante | 3 | Ver tareas, realizar entregas, ver calificaciones |

### Crear usuarios de prueba

```bash
php artisan tinker
```

```php
// Crear un docente
App\Models\User::factory()->docente()->create([
    'name' => 'Profesor Test',
    'email' => 'docente@test.com',
]);

// Crear un estudiante
App\Models\User::factory()->estudiante()->create([
    'name' => 'Estudiante Test',
    'email' => 'estudiante@test.com',
]);
```

## Testing

El proyecto incluye una suite completa de pruebas:

```bash
# Ejecutar todas las pruebas
php artisan test

# Ejecutar con cobertura
php artisan test --coverage

# Ejecutar pruebas especificas
php artisan test --filter=TareaControllerTest
```

### Pruebas Incluidas

- **Unit Tests**: TareaTest (modelos, accessors, scopes)
- **Feature Tests**: 
  - TareaControllerTest (CRUD completo)
  - EntregaControllerTest (entregas y descargas)
  - AuthenticationTest (login, logout)
  - RegistrationTest (registro de usuarios)
  - ProfileTest (edicion de perfil)
  - PasswordResetTest (recuperacion de contraseña)

## Rutas de la API

### Rutas Publicas
| Metodo | Ruta | Descripcion |
|--------|------|-------------|
| GET | `/` | Redirige al login |
| GET | `/login` | Pagina de inicio de sesion |
| GET | `/register` | Pagina de registro |

### Rutas de Docentes (Prefijo: /docente)
| Metodo | Ruta | Descripcion |
|--------|------|-------------|
| GET | `/docente/tareas` | Listado de tareas |
| GET | `/docente/tareas/create` | Formulario crear tarea |
| POST | `/docente/tareas` | Guardar nueva tarea |
| GET | `/docente/tareas/{tarea}` | Ver detalles y entregas |
| GET | `/docente/tareas/{tarea}/edit` | Editar tarea |
| PUT | `/docente/tareas/{tarea}` | Actualizar tarea |
| DELETE | `/docente/tareas/{tarea}` | Eliminar tarea (soft delete) |
| POST | `/docente/calificaciones` | Crear calificacion |
| PUT | `/docente/calificaciones/{id}` | Actualizar calificacion |
| GET | `/docente/entregas/{id}/descargar` | Descargar archivo |

### Rutas de Estudiantes (Prefijo: /estudiante)
| Metodo | Ruta | Descripcion |
|--------|------|-------------|
| GET | `/estudiante/tareas/{tarea}` | Ver detalle de tarea |
| POST | `/estudiante/entregas/{tarea}` | Enviar entrega |

## Middleware y Seguridad

### CheckRole Middleware
Verifica que el usuario tenga el rol adecuado:

```php
Route::middleware(['auth', 'rol:docente'])->group(function () {
    // Solo docentes pueden acceder
});
```

### Rate Limiting
Las rutas estan protegidas con throttle para prevenir abusos:
```php
Route::middleware(['auth', 'rol:docente', 'throttle:60,1'])
```

### Policies
Autorizacion granular mediante Policies:
```php
$this->authorize('update', $tarea);
$this->authorize('view', $entrega);
```

## Almacenamiento de Archivos

Los archivos se almacenan de forma segura:

| Tipo | Ubicacion | Acceso |
|------|-----------|--------|
| Guias de tareas | `storage/app/public/guias/` | Publico |
| Entregas | `storage/app/private/entregas/` | Privado |

## Comandos Utiles

```bash
# Limpiar cache
php artisan optimize:clear

# Optimizar para produccion
php artisan optimize
composer install --optimize-autoloader --no-dev

# Verificar codigo con Pint
./vendor/bin/pint

# Migraciones frescas con seeders
php artisan migrate:fresh --seed

# Ver rutas registradas
php artisan route:list
```

## Despliegue en Produccion

1. Configurar variables de entorno:
   ```env
   APP_ENV=production
   APP_DEBUG=false
   ```

2. Optimizar la aplicacion:
   ```bash
   composer install --optimize-autoloader --no-dev
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   npm run build
   ```

3. Configurar permisos:
   ```bash
   chmod -R 775 storage bootstrap/cache
   ```

## Contribuir

1. Fork el proyecto
2. Crear rama para feature (`git checkout -b feature/NuevaCaracteristica`)
3. Commit de cambios (`git commit -m 'Agregar nueva caracteristica'`)
4. Push a la rama (`git push origin feature/NuevaCaracteristica`)
5. Abrir Pull Request

## Licencia

Este proyecto esta disponible bajo la licencia MIT.

## Autor

**Elvis **
- GitHub: [@elviisch26](https://github.com/elviisch26)

## Agradecimientos

- Universidad Laica Eloy Alfaro de Manabi (ULEAM)
- Materia: Tecnologias Emergentes
- Framework Laravel y su comunidad

---

Desarrollado con Laravel 12 como proyecto academico para la materia de Tecnologias Emergentes.
