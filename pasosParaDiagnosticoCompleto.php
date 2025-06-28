 Pasos para Ejecutar el Diagnóstico:
1. Asegúrate de que XAMPP esté corriendo
✅ Apache debe estar activo
✅ MySQL debe estar activo
2. Abre tu navegador web y ve a:
http://localhost/Mis_Proyectos/IFTS12-LaCanchitaDeLosPibes-POO/diagnostico_correos.php
3. El diagnóstico se ejecutará automáticamente y te mostrará:
✅ Estado de las variables de entorno (.env)
✅ Conexión a la base de datos
✅ Configuración de PHPMailer
✅ Usuarios con emails válidos
✅ Logs del sistema
4. Desde el diagnóstico puedes:
🚀 Ejecutar Prueba de Envío (botón azul)
📧 Test Básico de Correo (botón verde)
🏟️ Test Completo de Reserva (botón azul claro)
🗄️ Verificar Base de Datos (botón amarillo)
📋 Orden Recomendado de Ejecución:
Primero: Ejecuta el diagnóstico principal
Si todo está verde: Haz clic en "🚀 Ejecutar Prueba de Envío"
Si hay problemas: Ejecuta los tests individuales:
Primero: "🗄️ Verificar Base de Datos"
Luego: "📧 Test Básico de Correo"
Finalmente: "🏟️ Test Completo de Reserva"