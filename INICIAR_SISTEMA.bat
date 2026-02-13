@echo off
TITLE Iniciando Sistema de Inventario...
COLOR 0A

echo ========================================================
echo      SISTEMA DE GESTION DE INVENTARIO Y VENTAS
echo ========================================================
echo.
echo [1/3] Verificando servicios de base de datos...

:: Intentar iniciar MySQL si no esta corriendo (ajusta la ruta si es diferente)
IF EXIST "C:\xampp\mysql\bin\mysqld.exe" (
    start /min "" "C:\xampp\mysql_start.bat"
) ELSE (
    echo ADVERTENCIA: No se encontro XAMPP en la ruta estandar.
    echo Asegurese de que Apache y MySQL esten iniciados manualmente.
)

echo [2/3] Verificando servidor web...

:: Intentar iniciar Apache si no esta corriendo
IF EXIST "C:\xampp\apache\bin\httpd.exe" (
    start /min "" "C:\xampp\apache_start.bat"
)

:: Esperar unos segundos para que los servicios arranquen
timeout /t 5 /nobreak >nul

echo [3/3] Abriendo el sistema...
echo.
echo  --------------------------------------------------
echo   El sistema se abrira en su navegador predeterminado.
echo   No cierre esta ventana si los servicios (Apache/MySQL) 
echo   dependen de ella, o minimicela.
echo  --------------------------------------------------
echo.

:: Abrir el navegador en la URL local
start http://localhost/sistemaInventario/index.php

:: Opcional: Cerrar esta ventana de comandos automaticamente
:: exit
