# Instalación Paso a Paso - Sistema de Biblioteca

## Prerrequisitos

✅ Docker Desktop instalado y corriendo
✅ Git instalado
✅ Puertos disponibles: 3000, 5432, 5050 y 8000

## Paso 1: Clonar y Preparar

```powershell
# Navegar al directorio del proyecto
cd "C:\Users\migue\Documents\___CodeS\Prueba-Nouesmalt"

# Verificar estructura
ls
```

## Paso 2: Configuración Inicial

### Backend

```powershell
# Copiar archivo de configuración
cd backend
copy .env.example .env
cd ..
```

### Frontend

```powershell
# Verificar configuración
cd frontend
cat .env
cd ..
```

## Paso 3: Levantar Docker

```powershell
# Iniciar todos los servicios
docker-compose up -d

# Verificar que los contenedores estén corriendo
docker ps
# Deberías ver: biblioteca_postgres, biblioteca_backend, biblioteca_frontend
```

## Paso 4: Configurar Backend Laravel

El backend se configura automáticamente al levantar Docker, pero si necesitas reconfigurar:

```powershell
# Reinstalar dependencias de Composer (opcional)
docker exec -it biblioteca_backend composer install

# Regenerar key de aplicación (opcional)
docker exec -it biblioteca_backend php artisan key:generate

# Re-ejecutar migraciones (opcional)
docker exec -it biblioteca_backend php artisan migrate:fresh --seed

# Dar permisos a storage (si hay problemas de permisos)
docker exec -it biblioteca_backend chmod -R 775 storage bootstrap/cache
```

**Nota:** Al levantar Docker con `docker-compose up -d`, el sistema automáticamente:

- Instala dependencias de Composer
- Genera la clave de aplicación
- Ejecuta las migraciones
- Carga los datos de prueba (seeder)

## Paso 5: Verificar Instalación

### Probar Backend

```powershell
# Probar API
curl http://localhost:8000/api/users
# Debería devolver JSON con lista de usuarios
```

### Probar Frontend

```
Abrir navegador en: http://localhost:3000
```

## Paso 6: Ejecutar Tests (Opcional)

```powershell
# Ejecutar todos los tests
docker exec -it biblioteca_backend php artisan test

# Ver resultado esperado:
# Tests:  24 passed
```

## 🎉 ¡Listo!

Tu sistema de biblioteca está funcionando en:

- **Frontend**: http://localhost:3000
- **Backend API**: http://localhost:8000
- **pgAdmin**: http://localhost:5050
- **Base de datos**: PostgreSQL en puerto 5432

🌐 pgAdmin: http://localhost:5050

### Credenciales:

- Email: admin@biblioteca.com
- Password: admin123

## Credenciales de Base de Datos

Si necesitas conectarte directamente:

- **Host**: localhost
- **Puerto**: 5432
- **Base de datos**: biblioteca
- **Usuario**: biblioteca_user
- **Contraseña**: biblioteca_pass

### Acceder con pgAdmin

1. Abre http://localhost:5050
2. Inicia sesión:
   - **Email**: admin@biblioteca.com
   - **Password**: admin123
3. El servidor **"Biblioteca PostgreSQL"** ya aparece preconfigurado en el panel izquierdo
4. Haz clic en el servidor para conectar
5. Ingresa la contraseña de PostgreSQL: **biblioteca_pass**
6. Marca la casilla **"Save password"** para no tener que ingresarla de nuevo

**Nota**: El servidor se configura automáticamente al levantar Docker. Solo necesitas ingresar la contraseña de la base de datos la primera vez.

## Problemas Comunes

### Error: Puerto ya en uso

**Problema**: El puerto 8000 o 3000 ya está ocupado

**Solución**:

```powershell
# Ver qué está usando el puerto
netstat -ano | findstr :8000

# Matar el proceso (reemplaza PID con el número que aparece)
taskkill /PID [número] /F

# O cambiar el puerto en docker-compose.yml
```

### Error: Base de datos no conecta

**Problema**: Laravel no conecta con PostgreSQL

**Solución**:

```powershell
# Ver logs de PostgreSQL
docker-compose logs postgres

# Reiniciar contenedor
docker-compose restart postgres

# Esperar 10 segundos y volver a intentar
```

### Error: Composer no encuentra paquetes

**Problema**: Composer install falla

**Solución**:

```powershell
# Limpiar caché de Composer
docker exec -it biblioteca_backend composer clear-cache

# Intentar de nuevo
docker exec -it biblioteca_backend composer install
```

### Error: Permisos en Windows

**Problema**: Errores de permisos en storage/

**Solución**:

```powershell
# Ejecutar dentro del contenedor
docker exec -it biblioteca_backend bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
exit
```

## Comandos Útiles

### Ver logs en tiempo real

```powershell
# Todos los servicios
docker-compose logs -f

# Solo backend
docker-compose logs -f backend

# Solo frontend
docker-compose logs -f frontend
```

### Detener servicios

```powershell
# Detener
docker-compose down

# Detener y eliminar volúmenes (CUIDADO: borra la BD)
docker-compose down -v
```

### Reiniciar un servicio

```powershell
# Reiniciar backend
docker-compose restart backend

# Reiniciar base de datos
docker-compose restart postgres
```

### Acceder al contenedor

```powershell
# Backend (Laravel)
docker exec -it biblioteca_backend bash

# Frontend (Vue.js)
docker exec -it biblioteca_frontend sh

# Base de datos
docker exec -it biblioteca_postgres psql -U biblioteca_user -d biblioteca
```

## Datos de Prueba Incluidos

El seeder crea automáticamente al levantar Docker:

**Usuarios:**

- Juan Pérez García (DNI: 12345678A)
- María García López (DNI: 23456789B)
- Carlos López Martínez (DNI: 34567890C)
- Ana Martínez Ruiz (DNI: 45678901D)

**Libros:**

- Clean Code - Robert C. Martin (ISBN: 9780132350884)
- Domain-Driven Design - Eric Evans (ISBN: 9780321125217)
- Design Patterns - Gang of Four (ISBN: 9780201633610)
- The Pragmatic Programmer - Andrew Hunt (ISBN: 9780135957059)
- Refactoring - Martin Fowler (ISBN: 9780201485677)

**Préstamos:**

- Usuario 1 tiene 1 préstamo devuelto y 1 activo
- Usuario 2 tiene 1 préstamo activo

## Probar la Funcionalidad

### 1. Crear un usuario

```powershell
curl -X POST http://localhost:8000/api/users `
  -H "Content-Type: application/json" `
  -d '{\"nombre\":\"Pedro\",\"apellidos\":\"Sánchez Gómez\",\"dni\":\"87654321Z\"}'
```

### 2. Crear un libro

```powershell
curl -X POST http://localhost:8000/api/books `
  -H "Content-Type: application/json" `
  -d '{\"titulo\":\"Libro de Prueba\",\"autor\":\"Autor de Prueba\",\"isbn\":\"9781234567890\"}'
```

### 3. Crear un préstamo

```powershell
# Primero obtén los IDs (UUID) de usuario y libro de /api/users y /api/books
curl -X POST http://localhost:8000/api/loans `
  -H "Content-Type: application/json" `
  -d '{\"user_id\":\"<UUID-del-usuario>\",\"book_id\":\"<UUID-del-libro>\"}'
```

### 4. Obtener reporte

```powershell
curl "http://localhost:8000/api/loans/report?start_date=2024-01-01&end_date=2024-12-31"
```

## Verificar Tests

```powershell
# Test de usuarios
docker exec -it biblioteca_backend php artisan test --filter=UserApiTest

# Test de libros
docker exec -it biblioteca_backend php artisan test --filter=BookApiTest

# Test de préstamos (incluye límite de 3)
docker exec -it biblioteca_backend php artisan test --filter=LoanApiTest
```

## 🎯 Siguiente Paso

Una vez verificado que todo funciona:

1. Abre el frontend en http://localhost:3000
2. Navega por las diferentes secciones
3. Prueba crear usuarios, libros y préstamos
4. Verifica que no puedes crear más de 3 préstamos activos por usuario
5. Genera reportes con diferentes rangos de fechas

## 📚 Documentación Adicional

- [README.md](README.md) - Documentación completa del proyecto
- [QUICKSTART.md](QUICKSTART.md) - Comandos útiles
- [RESUMEN.md](RESUMEN.md) - Resumen ejecutivo

---

**¿Necesitas ayuda?** Revisa los logs con `docker-compose logs -f`
