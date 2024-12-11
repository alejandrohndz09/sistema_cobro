<!DOCTYPE html>
<html>
<head>
    <title>Credenciales de Acceso</title>
</head>
<body>
    <h1>¡Bienvenido a nuestra plataforma!</h1>
    <p>Hola, {{ $usuario }}.</p>
    <p>Tu cuenta ha sido creada exitosamente. Estas son tus credenciales de acceso:</p>
    <ul>
        <li><strong>Usuario:</strong> {{ $usuario }}</li>
        <li><strong>Contraseña:</strong> {{ $clave }}</li>
    </ul>
    <p>Te recomendamos cambiar tu contraseña al iniciar sesión por primera vez.</p>
    <p>Saludos,</p>
    <p>El equipo de soporte</p>
</body>
</html>
