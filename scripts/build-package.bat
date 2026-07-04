@echo off
setlocal EnableDelayedExpansion

echo ==========================================
echo BUILD PACKAGE
echo ==========================================

set PROJECT_NAME=ERP-STAGGING-NEW

if "%BUILD_NUMBER%"=="" (
    set BUILD_NUMBER=LOCAL
)

set ARTIFACT_DIR=build\artifacts

set ARTIFACT_NAME=%PROJECT_NAME%_%BUILD_NUMBER%.zip

echo.

echo Creating artifact folder...

if not exist "%ARTIFACT_DIR%" (
    mkdir "%ARTIFACT_DIR%"
)

echo Cleaning previous ZIP...

del /q "%ARTIFACT_DIR%\*.zip" >nul 2>&1

echo Creating ZIP...

powershell -NoProfile -Command ^
"Compress-Archive -Path assets,classes,config,includes,modules,vendor,index.php,composer.json,composer.lock -DestinationPath '%ARTIFACT_DIR%\%ARTIFACT_NAME%' -Force"

if errorlevel 1 (
    echo ERROR : ZIP creation failed.
    exit /b 1
)

if not exist "%ARTIFACT_DIR%\%ARTIFACT_NAME%" (
    echo ERROR : ZIP not found.
    exit /b 1
)

echo ZIP created.

echo Creating manifest...

(
echo Project=%PROJECT_NAME%
echo Build=%BUILD_NUMBER%
echo Date=%DATE%
echo Time=%TIME%
)> "%ARTIFACT_DIR%\manifest.txt"

echo Manifest created.

echo Creating checksum...

certutil -hashfile "%ARTIFACT_DIR%\%ARTIFACT_NAME%" SHA256 > "%ARTIFACT_DIR%\checksum.sha256"

if errorlevel 1 (
    echo ERROR : Checksum failed.
    exit /b 1
)

echo %ARTIFACT_NAME% > "%ARTIFACT_DIR%\latest.txt"

echo.

echo ==========================================
echo BUILD PACKAGE SUCCESS
echo ==========================================

exit /b 0