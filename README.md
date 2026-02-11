# Sistema de Gestión de Biblioteca

Sistema de gestión de biblioteca desarrollado con **Arquitectura Hexagonal** y **Domain-Driven Design (DDD)**, implementado con Laravel, Vue.js (TypeScript) y PostgreSQL usando Docker.

## 📚 Documentación

- **[QUICKSTART.md](QUICKSTART.md)** - Guía rápida para empezar (recomendado para nuevos usuarios)
- **[INSTALL.md](INSTALL.md)** - Instrucciones detalladas de instalación paso a paso
- **[API.md](API.md)** - Documentación completa de endpoints REST
- **[RESUMEN.md](RESUMEN.md)** - Resumen ejecutivo y respuestas a preguntas técnicas

## 🏗️ Arquitectura

### Arquitectura Hexagonal (Ports & Adapters)

El proyecto sigue la arquitectura hexagonal con las siguientes capas:

#### 1. **Domain (Núcleo de negocio)**

- **Entidades**: `User`, `Book`, `Loan` - Modelos de dominio ricos con lógica de negocio
- **Value Objects**: `UserId`, `UserEmail`, `UserName`, `BookId`, `ISBN`, etc.
- **Interfaces de Repositorio**: Contratos que definen operaciones sin dependencias de infraestructura
- **Excepciones de Dominio**: `MaxLoansExceededException`

#### 2. **Application (Casos de uso)**

- **Use Cases**: Orquestan la lógica de negocio
  - `CreateUserUseCase`, `CreateBookUseCase`, `CreateLoanUseCase`
  - `ReturnBookUseCase`, `GetLoansByDateRangeUseCase`
- **DTOs/Requests**: Objetos de transferencia de datos

#### 3. **Infrastructure (Adaptadores)**

- **Controllers HTTP**: Puntos de entrada de la API REST
- **Repositorios Eloquent**: Implementaciones concretas de las interfaces del dominio
- **Modelos Eloquent**: Mapeo ORM con la base de datos
- **Migraciones**: Esquema de base de datos

```
backend/
├── src/
│   ├── Domain/              # Capa de Dominio
│   │   ├── User/
│   │   │   ├── User.php
│   │   │   ├── UserRepository.php (interface)
│   │   │   └── ValueObjects/
│   │   ├── Book/
│   │   └── Loan/
│   ├── Application/         # Capa de Aplicación
│   │   ├── User/
│   │   │   ├── Create/
│   │   │   ├── Find/
│   │   │   └── ListAll/
│   │   ├── Book/
│   │   └── Loan/
│   └── Infrastructure/      # Capa de Infraestructura
│       ├── Http/
│       │   └── Controllers/
│       └── Persistence/
│           └── Eloquent/
│               ├── Models/
│               └── Repositories/
```

## 🎯 Características Implementadas

### ✅ Requisitos Funcionales

1. **Gestión de Usuarios y Libros**
   - CRUD de usuarios con validación de email único
   - CRUD de libros con validación de ISBN único
   - Validaciones con Value Objects en la capa de dominio

2. **Sistema de Préstamos**
   - Creación de préstamos con validación de usuario y libro
   - **Límite de 3 préstamos activos por usuario** (regla de negocio)
   - Devolución de libros
   - Tracking de fechas de préstamo y devolución

3. **Reportes por Periodo**
   - Endpoint `/api/loans/report` que acepta rango de fechas
   - Retorna usuarios con total de préstamos en el periodo

4. **Contenedores Docker**
   - PostgreSQL
   - pgAdmin (gestión de base de datos)
   - Laravel Backend
   - Vue.js Frontend

### ✅ Requisitos Técnicos

1. **Patrones de Diseño Utilizados**
   - **Repository Pattern**: Abstracción de acceso a datos
     - Interfaces en el dominio, implementaciones en infraestructura
     - Permite cambiar la fuente de datos sin afectar la lógica de negocio
   - **Dependency Injection**: Inyección de dependencias en controllers y use cases
     - Configurado en `AppServiceProvider`
     - Facilita testing y desacoplamiento
   - **Strategy Pattern**: Validación de límite de préstamos
     - Lógica encapsulada en `CreateLoanUseCase`
     - Fácil de modificar sin afectar otras partes del sistema
   - **Factory Pattern**: Métodos estáticos para crear entidades
     - `User::create()`, `Book::create()`, `Loan::create()`

2. **Principios SOLID**
   - **S - Single Responsibility**: Cada clase tiene una única responsabilidad
     - Use Cases específicos para cada operación
     - Value Objects con validaciones propias
   - **O - Open/Closed**: Abierto a extensión, cerrado a modificación
     - Interfaces de repositorios permiten nuevas implementaciones
   - **L - Liskov Substitution**: Las implementaciones respetan los contratos
     - `EloquentUserRepository` implementa `UserRepository`
   - **I - Interface Segregation**: Interfaces específicas y cohesivas
     - Repositorios con métodos específicos a su dominio
   - **D - Dependency Inversion**: Dependencias de abstracciones
     - Use Cases dependen de interfaces, no de implementaciones concretas

3. **Pruebas Automáticas**
   - Tests de integración para cada endpoint
   - Cobertura de casos exitosos y fallidos
   - Tests unitarios de validaciones
   - Tests de reglas de negocio (límite de 3 préstamos)
   - PHPUnit configurado con RefreshDatabase

4. **Calidad del Código**
   - Namespaces organizados por dominio
   - Type hints en PHP 8.2
   - TypeScript en frontend
   - Manejo de errores consistente
   - Logging de operaciones importantes

## 🚀 Instalación y Uso

### Requisitos Previos

- Docker Desktop instalado
- Git

### Pasos de Instalación

1. **Clonar el repositorio**

```bash
git clone <repository-url>
cd Prueba\ 001
```

2. **Configurar variables de entorno**

```bash
# Backend
cd backend
cp .env.example .env
cd ..
```

3. **Levantar los contenedores**

```bash
docker-compose up -d
```

4. **Instalar dependencias del backend**

```bash
docker exec -it biblioteca_backend composer install
```

5. **Generar clave de aplicación**

```bash
docker exec -it biblioteca_backend php artisan key:generate
```

6. **Ejecutar migraciones y seeders**

```bash
docker exec -it biblioteca_backend php artisan migrate --seed
```

7. **Acceder a la aplicación**

- Frontend: http://localhost:3000
- Backend API: http://localhost:8000
- pgAdmin: http://localhost:5050 (admin@biblioteca.com / admin123)

### Ejecutar Tests

```bash
docker exec -it biblioteca_backend php artisan test
```

Con cobertura:

```bash
docker exec -it biblioteca_backend php artisan test --coverage
```

## 📚 API Endpoints

### Usuarios

- `GET /api/users` - Listar todos los usuarios
- `POST /api/users` - Crear usuario
- `GET /api/users/{id}` - Obtener usuario por ID

### Libros

- `GET /api/books` - Listar todos los libros
- `POST /api/books` - Crear libro
- `GET /api/books/{id}` - Obtener libro por ID

### Préstamos

- `GET /api/loans` - Listar todos los préstamos
- `POST /api/loans` - Crear préstamo
- `POST /api/loans/{id}/return` - Devolver libro
- `GET /api/loans/report?start_date=YYYY-MM-DD&end_date=YYYY-MM-DD` - Reporte por periodo

### Ejemplos de Uso

**Crear usuario:**

```bash
curl -X POST http://localhost:8000/api/users \
  -H "Content-Type: application/json" \
  -d '{"name":"Juan Pérez","email":"juan@example.com"}'
```

**Crear libro:**

```bash
curl -X POST http://localhost:8000/api/books \
  -H "Content-Type: application/json" \
  -d '{"title":"Clean Code","author":"Robert Martin","isbn":"9780132350884"}'
```

**Crear préstamo:**

```bash
curl -X POST http://localhost:8000/api/loans \
  -H "Content-Type: application/json" \
  -d '{"user_id":1,"book_id":1}'
```

**Obtener reporte:**

```bash
curl "http://localhost:8000/api/loans/report?start_date=2024-01-01&end_date=2024-12-31"
```

## 🧪 Testing

El proyecto incluye tests automáticos completos:

### Tests de Usuario (UserApiTest)

- ✅ Listar usuarios
- ✅ Crear usuario con datos válidos
- ✅ Validar email duplicado
- ✅ Validar formato de email
- ✅ Validar longitud de nombre
- ✅ Obtener usuario por ID
- ✅ Error 404 cuando no existe

### Tests de Libro (BookApiTest)

- ✅ Listar libros
- ✅ Crear libro con datos válidos
- ✅ Validar ISBN duplicado
- ✅ Validar formato de ISBN
- ✅ Obtener libro por ID
- ✅ Error 404 cuando no existe

### Tests de Préstamo (LoanApiTest)

- ✅ Listar préstamos
- ✅ Crear préstamo válido
- ✅ Validar usuario inexistente
- ✅ Validar libro inexistente
- ✅ **Validar límite de 3 préstamos activos**
- ✅ Permitir préstamo después de devolución
- ✅ Devolver libro
- ✅ Validar devolución duplicada
- ✅ Obtener reporte por rango de fechas
- ✅ Validar formato de fechas
- ✅ Validar orden de fechas

## ⚠️ Manejo de Errores

El proyecto implementa un sistema consistente de manejo de errores en múltiples niveles:

### Capa de Dominio

Excepciones específicas del negocio:

- `MaxLoansExceededException` - Cuando usuario intenta más de 3 préstamos activos
- `InvalidArgumentException` - Para valores inválidos (UUIDs malformados, IDs vacíos)

### Capa de Aplicación (Use Cases)

Valida datos de entrada y propaga excepciones del dominio hacia los controllers

### Capa de Infraestructura (Controllers)

Captura excepciones y retorna respuestas HTTP estructuradas con códigos de estado apropiados

### Códigos HTTP

- **200** - Operación exitosa
- **201** - Recurso creado exitosamente
- **400** - Lógica de negocio violada (ej: usuario excede límite de préstamos)
- **404** - Recurso no encontrado
- **422** - Validación fallida (datos enviados en formato inválido)
- **500** - Error inesperado del servidor

### Respuestas de Error

Todas las respuestas de error siguen este formato:

```json
{
  "success": false,
  "message": "Descripción del error",
  "errors": {} // Solo en validaciones (422)
}
```

### Logging

Todos los errores internos (500) se registran automáticamente en `storage/logs/laravel.log` con contexto completo para debugging

## 📊 Modelo de Datos

```sql
CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

CREATE TABLE books (
    id SERIAL PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(100) NOT NULL,
    isbn VARCHAR(20) UNIQUE NOT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

CREATE TABLE loans (
    id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES users(id) ON DELETE CASCADE,
    book_id INTEGER REFERENCES books(id) ON DELETE CASCADE,
    loan_date TIMESTAMP NOT NULL,
    return_date TIMESTAMP NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

## 🎨 Frontend (Vue.js + TypeScript)

- Framework: Vue 3 con Composition API
- TypeScript para type safety
- Vue Router para navegación
- Axios para peticiones HTTP
- Pinia para state management
- Vite como build tool

### Características del Frontend

- Interfaz intuitiva y responsive
- Validación de formularios
- Mensajes de éxito/error
- Tabla de datos con información en tiempo real
- Reportes visuales con filtrado por fechas

## 📋 Respuestas a las Preguntas

### 1. Flujo de trabajo con Git para varios desarrolladores

**Git Flow** es el modelo recomendado:

**Ramas principales:**

- `main` o `master`: Código en producción, siempre estable
- `develop`: Rama de desarrollo, integración de features

**Ramas de soporte:**

- `feature/*`: Nuevas funcionalidades
- `bugfix/*`: Corrección de bugs
- `hotfix/*`: Correcciones urgentes en producción
- `release/*`: Preparación de versiones

**Flujo de trabajo:**

1. **Desarrollo de features:**

   ```bash
   git checkout develop
   git checkout -b feature/loan-system
   # ... desarrollo ...
   git commit -m "feat: implement loan creation"
   git push origin feature/loan-system
   # Pull Request → develop
   ```

2. **Code Review:**
   - Todo código debe pasar por Pull Request
   - Al menos 1 aprobación requerida
   - Tests automáticos deben pasar

3. **Integración:**

   ```bash
   # Merge a develop después de aprobación
   git checkout develop
   git merge --no-ff feature/loan-system
   ```

4. **Release:**

   ```bash
   git checkout -b release/1.0.0 develop
   # Ajustes finales, versioning
   git checkout main
   git merge --no-ff release/1.0.0
   git tag -a v1.0.0
   git checkout develop
   git merge --no-ff release/1.0.0
   ```

5. **Hotfixes:**
   ```bash
   git checkout -b hotfix/critical-bug main
   # Fix the bug
   git checkout main
   git merge --no-ff hotfix/critical-bug
   git checkout develop
   git merge --no-ff hotfix/critical-bug
   ```

**Convenciones:**

- Commits semánticos: `feat:`, `fix:`, `refactor:`, `test:`, `docs:`
- Branch naming: `tipo/descripcion-corta`
- Pull Requests descriptivos con contexto

### 2. Principio SOLID más importante

**Dependency Inversion Principle (DIP)** - El más importante por:

**Razones:**

1. **Base de la arquitectura limpia**: Permite que las capas de alto nivel (dominio) no dependan de las de bajo nivel (infraestructura)

2. **Testability**: Facilita el testing al poder mockear dependencias

   ```php
   // Puedo testear sin base de datos real
   $mockRepo = Mockery::mock(UserRepository::class);
   $useCase = new CreateUserUseCase($mockRepo);
   ```

3. **Flexibilidad**: Permite cambiar implementaciones sin modificar el código cliente

   ```php
   // Cambiar de Eloquent a MongoDB sin tocar use cases
   $this->app->bind(UserRepository::class, MongoUserRepository::class);
   ```

4. **Habilita otros principios**: DIP es la base que permite implementar correctamente los demás principios SOLID

**Ejemplo en el proyecto:**

```php
// Use Case depende de la abstracción, no de la implementación
class CreateLoanUseCase
{
    public function __construct(
        private readonly LoanRepository $loanRepository,  // Interface
        private readonly UserRepository $userRepository,  // Interface
        private readonly BookRepository $bookRepository   // Interface
    ) {}
}

// Laravel resuelve las implementaciones concretas
// AppServiceProvider.php
$this->app->bind(LoanRepository::class, EloquentLoanRepository::class);
```

### 3. Patrones de diseño utilizados habitualmente

Además de MVC, Repository:

**1. Strategy Pattern**

- **Uso**: Validaciones complejas, algoritmos intercambiables
- **Ejemplo**: Sistema de notificaciones (email, SMS, push)

```php
interface NotificationStrategy {
    public function send(string $message): void;
}

class EmailNotification implements NotificationStrategy { }
class SMSNotification implements NotificationStrategy { }
```

**2. Factory Pattern**

- **Uso**: Creación de objetos complejos
- **Ejemplo**: Construcción de entidades con validaciones

```php
class UserFactory {
    public static function createFromRequest(array $data): User {
        return User::create(
            new UserName($data['name']),
            new UserEmail($data['email'])
        );
    }
}
```

**3. Observer Pattern**

- **Uso**: Eventos y listeners
- **Ejemplo**: Enviar email al crear préstamo

```php
// Event
class LoanCreated {
    public function __construct(public Loan $loan) {}
}

// Listener
class SendLoanNotification {
    public function handle(LoanCreated $event): void {
        // Send email
    }
}
```

**4. Adapter Pattern**

- **Uso**: Integrar bibliotecas externas
- **Ejemplo**: Adaptar diferentes servicios de pago

```php
interface PaymentGateway {
    public function charge(float $amount): bool;
}

class StripeAdapter implements PaymentGateway { }
class PayPalAdapter implements PaymentGateway { }
```

**5. Decorator Pattern**

- **Uso**: Añadir funcionalidad sin modificar clases
- **Ejemplo**: Logging, caché

```php
class CachedUserRepository implements UserRepository {
    public function __construct(
        private UserRepository $repository,
        private CacheInterface $cache
    ) {}

    public function findById(UserId $id): ?User {
        return $this->cache->remember(
            "user.{$id->value()}",
            fn() => $this->repository->findById($id)
        );
    }
}
```

### 4. Refactorización de código legacy sin documentación

**Enfoque estructurado:**

**Fase 1: Análisis (No tocar código)**

1. **Ejecutar la aplicación**: Entender qué hace en producción
2. **Mapear flujos principales**: User journeys críticos
3. **Identificar dependencias**: Base de datos, APIs externas, servicios
4. **Revisar logs**: Errores comunes, patrones de uso

**Fase 2: Estabilización**

1. **Tests de caracterización** (Characterization Tests):

   ```php
   // Capturar comportamiento actual, aunque sea incorrecto
   public function test_current_behavior() {
       $result = LegacyClass::doSomething($input);
       $this->assertEquals($expectedOutput, $result);
   }
   ```

2. **Configurar CI/CD**: Tests automáticos para detectar regresiones

3. **Añadir logs y monitoring**: Observar comportamiento en producción

**Fase 3: Refactorización incremental**

1. **Técnica del estrangulador** (Strangler Pattern):
   - Crear nueva funcionalidad al lado del código legacy
   - Redirigir tráfico gradualmente
   - Eliminar código viejo cuando está 100% migrado

2. **Extract Method/Class**:

   ```php
   // Antes: método de 200 líneas
   public function processOrder() {
       // 200 líneas de código espagueti
   }

   // Después: extraer responsabilidades
   public function processOrder() {
       $this->validateOrder();
       $this->calculateTotal();
       $this->applyDiscounts();
       $this->processPayment();
       $this->updateInventory();
   }
   ```

3. **Identificar seams** (puntos de extensión):

   ```php
   // Añadir interfaces para poder testear
   interface PaymentProcessor {
       public function process(Order $order): bool;
   }

   // Adaptar código legacy
   class LegacyPaymentAdapter implements PaymentProcessor {
       public function process(Order $order): bool {
           return LegacyPaymentSystem::processPayment($order);
       }
   }
   ```

**Fase 4: Mejora continua**

1. **Documentar mientras refactorizas**: README, diagramas
2. **Aplicar principios SOLID gradualmente**
3. **Pair programming con equipo**: Transferir conocimiento
4. **Code reviews estrictos**: Prevenir nuevo código legacy

**Principios clave:**

- ✅ **Nunca refactorizar sin tests**
- ✅ **Cambios pequeños e incrementales**
- ✅ **Mantener funcionalidad existente**
- ✅ **Deploy frecuente con feature flags**
- ✅ **Medir todo**: performance, errores, uso

**Herramientas útiles:**

- PHPStan/Psalm: Análisis estático
- PHP Metrics: Complejidad ciclomática
- Git blame: Entender contexto histórico
- Feature flags: Despliegue seguro

## 🛠️ Stack Tecnológico

- **Backend**: Laravel 11, PHP 8.2
- **Frontend**: Vue 3, TypeScript, Vite
- **Base de datos**: PostgreSQL 15
- **Contenedores**: Docker, Docker Compose
- **Testing**: PHPUnit
- **Code Quality**: PSR-12, Type Hints

## 📝 Licencia

MIT License

## 👨‍💻 Autor

Desarrollo de prueba técnica - Sistema de Biblioteca
