# API de Referencia - Sistema de Biblioteca

## Base URL

```
http://localhost:8000/api
```

## Endpoints Disponibles

### 👥 Usuarios

#### Listar todos los usuarios

```http
GET /users
```

**Respuesta exitosa (200):**

```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Juan Pérez",
      "email": "juan.perez@example.com",
      "created_at": "2024-01-15 10:30:00",
      "updated_at": "2024-01-15 10:30:00"
    }
  ]
}
```

#### Crear usuario

```http
POST /users
Content-Type: application/json

{
  "name": "Juan Pérez",
  "email": "juan.perez@example.com"
}
```

**Validaciones:**

- `name`: requerido, string, min:2, max:100
- `email`: requerido, email válido, único

**Respuesta exitosa (201):**

```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Juan Pérez",
    "email": "juan.perez@example.com",
    "created_at": "2024-01-15 10:30:00",
    "updated_at": null
  },
  "message": "User created successfully"
}
```

**Errores posibles:**

- 400: Email ya existe
- 422: Validación fallida

#### Obtener usuario por ID

```http
GET /users/{id}
```

**Respuesta exitosa (200):**

```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Juan Pérez",
    "email": "juan.perez@example.com",
    "created_at": "2024-01-15 10:30:00",
    "updated_at": null
  }
}
```

**Errores posibles:**

- 404: Usuario no encontrado

---

### 📚 Libros

#### Listar todos los libros

```http
GET /books
```

**Respuesta exitosa (200):**

```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "title": "Clean Code",
      "author": "Robert C. Martin",
      "isbn": "9780132350884",
      "created_at": "2024-01-15 10:30:00",
      "updated_at": "2024-01-15 10:30:00"
    }
  ]
}
```

#### Crear libro

```http
POST /books
Content-Type: application/json

{
  "title": "Clean Code",
  "author": "Robert C. Martin",
  "isbn": "9780132350884"
}
```

**Validaciones:**

- `title`: requerido, string, min:1, max:255
- `author`: requerido, string, min:2, max:100
- `isbn`: requerido, formato ISBN-10 o ISBN-13, único

**Respuesta exitosa (201):**

```json
{
  "success": true,
  "data": {
    "id": 1,
    "title": "Clean Code",
    "author": "Robert C. Martin",
    "isbn": "9780132350884",
    "created_at": "2024-01-15 10:30:00",
    "updated_at": null
  },
  "message": "Book created successfully"
}
```

**Errores posibles:**

- 400: ISBN ya existe
- 422: Validación fallida (formato ISBN inválido)

#### Obtener libro por ID

```http
GET /books/{id}
```

**Respuesta exitosa (200):**

```json
{
  "success": true,
  "data": {
    "id": 1,
    "title": "Clean Code",
    "author": "Robert C. Martin",
    "isbn": "9780132350884",
    "created_at": "2024-01-15 10:30:00",
    "updated_at": null
  }
}
```

**Errores posibles:**

- 404: Libro no encontrado

---

### 📋 Préstamos

#### Listar todos los préstamos

```http
GET /loans
```

**Respuesta exitosa (200):**

```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "user_id": 1,
      "book_id": 1,
      "loan_date": "2024-01-15 10:30:00",
      "return_date": null,
      "created_at": "2024-01-15 10:30:00",
      "updated_at": "2024-01-15 10:30:00"
    }
  ]
}
```

#### Crear préstamo

```http
POST /loans
Content-Type: application/json

{
  "user_id": 1,
  "book_id": 1
}
```

**Validaciones:**

- `user_id`: requerido, integer, debe existir
- `book_id`: requerido, integer, debe existir
- Usuario no puede tener más de 3 préstamos activos

**Respuesta exitosa (201):**

```json
{
  "success": true,
  "data": {
    "id": 1,
    "user_id": 1,
    "book_id": 1,
    "loan_date": "2024-01-15 10:30:00",
    "return_date": null,
    "created_at": "2024-01-15 10:30:00",
    "updated_at": null
  },
  "message": "Loan created successfully"
}
```

**Errores posibles:**

- 400: Usuario no encontrado
- 400: Libro no encontrado
- 400: Usuario ha alcanzado el máximo de préstamos activos (3)

#### Devolver libro

```http
POST /loans/{id}/return
```

**Respuesta exitosa (200):**

```json
{
  "success": true,
  "data": {
    "id": 1,
    "user_id": 1,
    "book_id": 1,
    "loan_date": "2024-01-15 10:30:00",
    "return_date": "2024-01-20 15:45:00",
    "created_at": "2024-01-15 10:30:00",
    "updated_at": "2024-01-20 15:45:00"
  },
  "message": "Book returned successfully"
}
```

**Errores posibles:**

- 404: Préstamo no encontrado
- 400: Libro ya ha sido devuelto

#### Reporte de préstamos por periodo

```http
GET /loans/report?start_date=2024-01-01&end_date=2024-12-31
```

**Parámetros:**

- `start_date`: requerido, formato YYYY-MM-DD
- `end_date`: requerido, formato YYYY-MM-DD, debe ser >= start_date

**Respuesta exitosa (200):**

```json
{
  "success": true,
  "data": [
    {
      "user_id": 1,
      "user_name": "Juan Pérez",
      "user_email": "juan.perez@example.com",
      "total_loans": 5
    },
    {
      "user_id": 2,
      "user_name": "María García",
      "user_email": "maria.garcia@example.com",
      "total_loans": 3
    }
  ],
  "period": {
    "start_date": "2024-01-01",
    "end_date": "2024-12-31"
  }
}
```

**Errores posibles:**

- 422: Formato de fecha inválido
- 422: Fecha de fin anterior a fecha de inicio

---

## Códigos de Estado HTTP

- `200 OK`: Operación exitosa
- `201 Created`: Recurso creado exitosamente
- `400 Bad Request`: Error en la solicitud (regla de negocio)
- `404 Not Found`: Recurso no encontrado
- `422 Unprocessable Entity`: Error de validación
- `500 Internal Server Error`: Error del servidor

## Estructura de Respuestas de Error

```json
{
  "success": false,
  "message": "Descripción del error"
}
```

## Ejemplos con cURL

### Crear usuario

```bash
curl -X POST http://localhost:8000/api/users \
  -H "Content-Type: application/json" \
  -d '{"name":"Juan Pérez","email":"juan@example.com"}'
```

### Crear libro

```bash
curl -X POST http://localhost:8000/api/books \
  -H "Content-Type: application/json" \
  -d '{"title":"Clean Code","author":"Robert Martin","isbn":"9780132350884"}'
```

### Crear préstamo

```bash
curl -X POST http://localhost:8000/api/loans \
  -H "Content-Type: application/json" \
  -d '{"user_id":1,"book_id":1}'
```

### Devolver libro

```bash
curl -X POST http://localhost:8000/api/loans/1/return
```

### Obtener reporte

```bash
curl "http://localhost:8000/api/loans/report?start_date=2024-01-01&end_date=2024-12-31"
```

## Ejemplos con PowerShell

### Crear usuario

```powershell
Invoke-RestMethod -Uri 'http://localhost:8000/api/users' `
  -Method Post `
  -ContentType 'application/json' `
  -Body '{"name":"Juan Pérez","email":"juan@example.com"}'
```

### Crear préstamo

```powershell
Invoke-RestMethod -Uri 'http://localhost:8000/api/loans' `
  -Method Post `
  -ContentType 'application/json' `
  -Body '{"user_id":1,"book_id":1}'
```

### Obtener reporte

```powershell
Invoke-RestMethod -Uri 'http://localhost:8000/api/loans/report?start_date=2024-01-01&end_date=2024-12-31'
```
