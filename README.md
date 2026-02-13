# Sistema de Inventario y Ventas (POS)

Sistema web completo para la gestión de inventarios y punto de venta, desarrollado con **PHP nativo** y **MySQL**. Diseñado para pequeñas y medianas empresas (PyMEs) que necesitan controlar su stock y ventas de manera eficiente.

## 🚀 Características Principales

*   **Gestión de Productos:**
    *   Control de inventario en tiempo real.
    *   Soporte para productos unitarios y a granel (decimales).
    *   Generación y lectura de códigos de barras.
    *   Alertas de stock bajo.
*   **Punto de Venta (POS):**
    *   Interfaz rápida para ventas.
    *   Búsqueda por código de barras o nombre.
    *   Cálculo automático de totales.
    *   Generación de tickets de venta.
*   **Administración:**
    *   Gestión de Categorías y Proveedores.
    *   Historial de movimientos (Entradas/Salidas).
    *   Reportes de ventas diarias.
*   **Seguridad y Usuarios:**
    *   Sistema de roles (Administrador y Vendedor).
    *   Autenticación segura con hash de contraseñas.
    *   Protección contra ataques CSRF.

## 🛠️ Tecnologías Utilizadas

*   **Backend:** PHP (POO, PDO, MVC).
*   **Frontend:** HTML5, CSS3 (Bootstrap 5), JavaScript.
*   **Base de Datos:** MySQL.

## 📋 Requisitos de Instalación

1.  Tener instalado **XAMPP** o cualquier servidor local con PHP y MySQL.
2.  Clonar este repositorio en la carpeta `htdocs`.
3.  Crear una base de datos en MySQL llamada `inventario_pymes`.
4.  Importar el archivo `database.sql` incluido en la raíz del proyecto.
5.  Configurar la conexión en `app/config/database.php` si tus credenciales son diferentes a las predeterminadas (`root`, sin contraseña).

## 🔐 Acceso Predeterminado

*   **Usuario:** `admin`
*   **Contraseña:** `admin123`

---
*Desarrollado con ❤️ para gestión eficiente de negocios.*
