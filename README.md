# Sistema de Cobro

## Descripción
El **Sistema de Cobro** es una aplicación web diseñada para facilitar la gestión integral de ventas y cobros, así como la administración de activos fijos dentro de una organización. Su enfoque modular permite manejar de manera eficiente tanto las operaciones comerciales como los recursos físicos de la empresa, promoviendo la automatización, el control de pagos a crédito y la supervisión de bienes.

## Módulos Funcionales

### 1. Gestión Comercial
Este módulo permite administrar las transacciones comerciales, los clientes, y el proceso de cobro.

#### Funcionalidades Clave
- **Gestión de Ventas**:  
  - Creación de ventas con opción de pago a crédito o contado.  
  - Generación de facturas en PDF para ventas realizadas.  
  - Actualización automática de inventarios.

- **Gestión de Cuotas**:  
  - Creación automática de cuotas basadas en condiciones de crédito.  
  - Cálculo de mora por pagos atrasados y actualización de estados de cuotas.  
  - Reportes detallados de pagos.

- **Gestión de Clientes**:  
  - Administración de clientes naturales y jurídicos.  
  - Validaciones específicas para documentos de identidad y registros fiscales.  
  - Almacenamiento de documentos en formato PDF.

#### Beneficios
- Mejora la eficiencia del proceso de ventas y cobros.
- Automatiza la creación de cuotas y el cálculo de intereses por mora.
- Centraliza la información de clientes y transacciones comerciales.

### 2. Gestión de Activo Fijo
Este módulo proporciona herramientas para el control y seguimiento de los activos físicos de la organización.

#### Funcionalidades Clave
- Registro y categorización de activos fijos.
- Asociación de activos con empresas y sucursales.
- Reportes sobre activos disponibles y su ubicación.

#### Beneficios
- Facilita el inventario y la supervisión de activos.
- Proporciona una estructura escalable para manejar recursos físicos.

## Tecnologías Utilizadas
- **Backend**: Laravel 11
- **Lenguaje de Programación**: PHP 8.1
- **Frontend**: Bootstrap 5, Blade Templates
- **Base de Datos**: MySQL
- **Generación de PDF**: DomPDF
- **Gestión de Dependencias**: Composer

## Propósito
El Sistema de Cobro busca automatizar los procesos de ventas, cobro y gestión de activos, proporcionando a las empresas herramientas efectivas para mejorar la eficiencia operativa, reducir errores y centralizar la administración de recursos críticos.
