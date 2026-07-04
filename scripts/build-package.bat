@echo off
setlocal EnableDelayedExpansion

echo ==========================================
echo BUILD PACKAGE STAGE
echo ==========================================

REM -------------------------------------------------
REM Configuration
REM -------------------------------------------------

set PROJECT_NAME=ERP-STAGGING-NEW
set BUILD_NUMBER=%BUILD_NUMBER%
set ARTIFACT_DIR=build\artifacts

if "%BUILD_NUMBER%"=="" set BUILD_NUMBER=LOCAL

set ARTIFACT_NAME=%PROJECT_NAME%_%BUILD_NUMBER%.zip

echo.
echo Project        : %PROJECT_NAME%
echo Build Number   : %BUILD_NUMBER%
echo Artifact       : %ARTIFACT_NAME%
echo.

REM -------------------------------------------------
REM Create Artifact Directory
REM -------------------------------------------------

if not exist "%ARTIFACT_DIR%" (
    mkdir "%ARTIFACT_DIR%"
)

REM -------------------------------------------------
REM Remove Old ZIP Files
REM -------------------------------------------------

del /q "%ARTIFACT_DIR%\*.zip" >nul 2>&1

REM -------------------------------------------------
REM Create ZIP Package
REM -------------------------------------------------

echo Creating deployment package...

powershell -NoProfile -Command ^
"Compress-Archive -Force ^
-Path ^
'assets', ^
'classes', ^
'config', ^
'includes', ^
'modules', ^
'vendor', ^
'index.php', ^
'composer.json', ^
'composer.lock' ^
-DestinationPath '%ARTIFACT_DIR%\%ARTIFACT_NAME%'"

if errorlevel 1 (
    echo ERROR: Failed to create ZIP package.
    exit /b 1
)

REM -------------------------------------------------
REM Verify ZIP
REM -------------------------------------------------

if not exist "%ARTIFACT_DIR%\%ARTIFACT_NAME%" (
    echo ERROR: Artifact not found.
    exit /b 1
)

echo ZIP package created successfully.

REM -------------------------------------------------
REM Generate Manifest
REM -------------------------------------------------

echo Generating manifest...

(
echo ===================================
echo ERP STAGING BUILD MANIFEST
echo ===================================
echo Project=%PROJECT_NAME%
echo Build=%BUILD_NUMBER%
echo Date=%DATE%
echo Time=%TIME%
echo Computer=%COMPUTERNAME%
echo User=%USERNAME%
) > "%ARTIFACT_DIR%\manifest.txt"

REM -------------------------------------------------
REM Generate SHA256
REM -------------------------------------------------

echo Generating SHA256 checksum...

powershell -NoProfile -Command ^
"Get-FileHash '%ARTIFACT_DIR%\%ARTIFACT_NAME%' -Algorithm SHA256 | Format-List > '%ARTIFACT_DIR%\checksum.sha256'"

REM -------------------------------------------------
REM Save Latest Artifact
REM -------------------------------------------------

echo %ARTIFACT_NAME% > "%ARTIFACT_DIR%\latest.txt"

echo.
echo ==========================================
echo BUILD PACKAGE COMPLETED SUCCESSFULLY
echo ==========================================
echo.

exit /b 0