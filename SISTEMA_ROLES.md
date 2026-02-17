# 🔐 Sistema de Autenticación y Roles - Documentación

## 📋 Resumen de Implementación

Se implementó un sistema completo de autenticación basado en roles para diferenciar entre **Usuarios Normales** y **Bibliotecarios**.

---

## 🎯 Flujo de Login

### **Cuando un usuario inicia sesión:**

1. Se validan las credenciales (correo y contraseña)
2. Se verifica el rol del usuario en la base de datos
3. Se redirige automáticamente según el rol:

```php
// Bibliotecario
if ($usuario->rol === 'bibliotecario') {
    redirect → /dashboard
}

// Usuario Normal
else {
    redirect → /usuario/catalogo
}
```

### **Cuando un usuario se registra:**

- Siempre se crea con `rol = 'usuario'`
- Se redirige automáticamente a: `/usuario/catalogo`

---

## 🛡️ Middlewares Creados

### **1. CheckBibliotecario**
- **Ubicación:** `app/Http/Middleware/CheckBibliotecario.php`
- **Función:** Verifica que el usuario tenga rol `bibliotecario`
- **Si falla:** Redirige a `/usuario/catalogo` con mensaje de error

### **2. CheckUsuario**
- **Ubicación:** `app/Http/Middleware/CheckUsuario.php`
- **Función:** Verifica que el usuario tenga rol `usuario`
- **Si falla:** Redirige a `/dashboard` con mensaje de error

---

## 🗺️ Rutas Protegidas

### **👨‍💼 Rutas de BIBLIOTECARIO**
Requieren: `middleware(['auth', 'bibliotecario'])`

| Ruta | Controlador | Vista | Descripción |
|------|-------------|-------|-------------|
| `/dashboard` | `BibliotecarioController@index` | `dashboards.bibliotecario.index` | Dashboard principal |
| `/dashboard/usuarios` | `BibliotecarioController@usuarios` | `dashboards.bibliotecario.usuarios` | Gestión de usuarios |
| `/dashboard/prestamos` | `BibliotecarioController@prestamos` | `dashboards.bibliotecario.prestamos` | Gestión de préstamos |
| `/dashboard/todos-libros` | `BibliotecarioController@libros` | `dashboards.bibliotecario.libros` | Gestión de libros |

### **👤 Rutas de USUARIO NORMAL**
Requieren: `middleware(['auth', 'usuario'])`

| Ruta | Controlador | Vista | Descripción |
|------|-------------|-------|-------------|
| `/usuario/catalogo` | `UsuarioDashboardController@catalogo` | `dashboards.usuario.catalogo` | Catálogo de libros |
| `/usuario/mis-prestamos` | `UsuarioDashboardController@misPrestamos` | `dashboards.usuario.mis-prestamos` | Sus préstamos |
| `/usuario/perfil` | `UsuarioDashboardController@perfil` | `dashboards.usuario.perfil` | Su perfil |

---

## ✅ Comportamiento del Sistema

### **Escenario 1: Usuario Normal intenta acceder a dashboard de admin**
```
Usuario con rol='usuario' → /dashboard
❌ Middleware detecta que NO es bibliotecario
→ Redirige a /usuario/catalogo
→ Muestra mensaje: "No tienes permisos para acceder a esta sección."
```

### **Escenario 2: Bibliotecario intenta acceder a dashboard de usuario**
```
Usuario con rol='bibliotecario' → /usuario/catalogo
❌ Middleware detecta que NO es usuario normal
→ Redirige a /dashboard
→ Muestra mensaje: "Esta sección es solo para usuarios normales."
```

### **Escenario 3: Usuario no autenticado intenta acceder a cualquier dashboard**
```
Usuario sin login → /dashboard o /usuario/catalogo
❌ Middleware detecta que NO está autenticado
→ Redirige a /login
→ Muestra mensaje: "Debes iniciar sesión."
```

---

## 🔄 Flujo Completo

```
┌─────────────┐
│   /login    │
└──────┬──────┘
       │
       ├──→ Validar Credenciales
       │
       ├──→ Login Exitoso
       │
       ├───┬─── ¿Rol?
       │   │
       │   ├─→ bibliotecario → /dashboard (Admin Panel)
       │   │                    ├─ /dashboard/usuarios
       │   │                    ├─ /dashboard/prestamos
       │   │                    └─ /dashboard/todos-libros
       │   │
       │   └─→ usuario → /usuario/catalogo (User Panel)
       │                 ├─ /usuario/mis-prestamos
       │                 └─ /usuario/perfil
       │
       └──→ Login Fallido → Volver a /login con error
```

---

## 📁 Archivos Modificados

1. ✅ `app/Http/Controllers/LoginController.php`
   - Redirección según rol en `login()`
   - Redirección a dashboard de usuario en `register()`
   - Método `logout()` activado

2. ✅ `app/Http/Middleware/CheckBibliotecario.php`
   - Middleware creado para verificar rol bibliotecario

3. ✅ `app/Http/Middleware/CheckUsuario.php`
   - Middleware creado para verificar rol usuario

4. ✅ `bootstrap/app.php`
   - Middlewares registrados como alias

5. ✅ `routes/web.php`
   - Middlewares aplicados a grupos de rutas

---

## 🧪 Cómo Probar

### **Crear usuario bibliotecario:**
```sql
UPDATE usuarios SET rol = 'bibliotecario' WHERE correo = 'admin@biblioteca.com';
```

### **Probar acceso:**
1. Login como usuario normal → Debe ir a `/usuario/catalogo`
2. Intentar acceder a `/dashboard` → Debe bloquearse
3. Login como bibliotecario → Debe ir a `/dashboard`
4. Intentar acceder a `/usuario/catalogo` → Debe bloquearse

---

## 🎉 Resultado Final

✅ **Login inteligente** - Redirige automáticamente según rol
✅ **Rutas protegidas** - No se puede acceder sin el rol correcto
✅ **Mensajes claros** - El usuario sabe por qué no puede acceder
✅ **Código limpio** - Lógica en controladores, no en rutas
✅ **Seguridad** - Middleware verifica permisos en cada petición
