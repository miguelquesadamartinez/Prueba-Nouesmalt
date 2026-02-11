# Guía Rápida de Inicio

## Inicio Rápido (5 minutos)

### 1. Levantar servicios

```bash
docker-compose up -d
```

### 2. Instalar dependencias del backend

```bash
docker exec -it biblioteca_backend bash -c "composer install && php artisan key:generate && php artisan migrate --seed"
```

### 3. Acceder a la aplicación

- **Frontend**: http://localhost:3000
- **API**: http://localhost:8000
- **pgAdmin**: http://localhost:5050 (admin@biblioteca.com / admin123)

## Comandos Útiles

### Backend (Laravel)

```bash
# Entrar al contenedor
docker exec -it biblioteca_backend bash

# Ejecutar migraciones
php artisan migrate

# Ejecutar seeders
php artisan db:seed

# Ejecutar tests
php artisan test

# Ver logs
php artisan log:tail

# Limpiar caché
php artisan cache:clear
```

### Frontend (Vue.js)

```bash
# Entrar al contenedor
docker exec -it biblioteca_frontend sh

# Instalar dependencias
npm install

# Ejecutar desarrollo
npm run dev

# Build para producción
npm run build
```

### Base de datos (PostgreSQL)

```bash
# Conectar a PostgreSQL
docker exec -it biblioteca_postgres psql -U biblioteca_user -d biblioteca

# Ver tablas
\dt

# Ver datos de una tabla
SELECT * FROM users;
```

### pgAdmin (Interfaz Web)

```bash
# Acceder en: http://localhost:5050
# Email: admin@biblioteca.com
# Password: admin123

# El servidor "Biblioteca PostgreSQL" ya aparece preconfigurado
# Al hacer clic en el servidor, ingresar:
# Password: biblioteca_pass
# Marcar "Save password" para guardarlo
```

### Docker

```bash
# Ver logs de todos los servicios
docker-compose logs -f

# Ver logs de un servicio específico
docker-compose logs -f backend

# Detener servicios
docker-compose down

# Detener y eliminar volúmenes
docker-compose down -v

# Reconstruir contenedores
docker-compose up -d --build
```

## Estructura de Endpoints

### Usuarios

```
GET    /api/users          # Listar usuarios
POST   /api/users          # Crear usuario
GET    /api/users/{id}     # Obtener usuario
```

### Libros

```
GET    /api/books          # Listar libros
POST   /api/books          # Crear libro
GET    /api/books/{id}     # Obtener libro
```

### Préstamos

```
GET    /api/loans                    # Listar préstamos
POST   /api/loans                    # Crear préstamo
POST   /api/loans/{id}/return        # Devolver libro
GET    /api/loans/report             # Reporte por fechas
  ?start_date=YYYY-MM-DD
  &end_date=YYYY-MM-DD
```

## Troubleshooting

### Puerto 8000 ya en uso

```bash
# Cambiar el puerto en docker-compose.yml
ports:
  - "8001:8000"  # Usar 8001 en lugar de 8000
```

### Permisos en Laravel

```bash
docker exec -it biblioteca_backend bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Base de datos no conecta

```bash
# Verificar que PostgreSQL está corriendo
docker ps | grep postgres

# Ver logs de PostgreSQL
docker-compose logs postgres
```

### Frontend no carga

```bash
# Verificar variables de entorno
cat frontend/.env

# Reinstalar dependencias
docker exec -it biblioteca_frontend sh
rm -rf node_modules package-lock.json
npm install
```
