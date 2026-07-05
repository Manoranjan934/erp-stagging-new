@echo off
setlocal

echo ==========================================
echo ENTERPRISE DEPLOYMENT
echo ==========================================

REM ==========================================
REM Configuration
REM ==========================================

set "ARTIFACT_DIR=build\artifacts"
set "ZIP_FILE=%ARTIFACT_DIR%\ERP-STAGGING-NEW_%BUILD_NUMBER%.zip"
set "TEMP_DEPLOY=D:\Xampp-org\htdocs\deployment-temp"

echo.
echo Build Number : %BUILD_NUMBER%
echo Artifact     : %ZIP_FILE%
echo Temp Folder  : %TEMP_DEPLOY%
echo.

REM ==========================================
REM Verify ZIP
REM ==========================================

if not exist "%ZIP_FILE%" (
    echo [ERROR] Artifact ZIP not found.
    exit /b 1
)

echo [PASS] Artifact ZIP found.

REM ==========================================
REM Clean Temp Folder
REM ==========================================

if exist "%TEMP_DEPLOY%" (
    rmdir /s /q "%TEMP_DEPLOY%"
)

mkdir "%TEMP_DEPLOY%"

echo [PASS] Temporary deployment folder prepared.

REM ==========================================
REM Extract ZIP
REM ==========================================

echo.
echo Extracting package...

powershell -Command "Expand-Archive -Path '%ZIP_FILE%' -DestinationPath '%TEMP_DEPLOY%' -Force"

if errorlevel 1 (
    echo [ERROR] Extraction failed.
    exit /b 1
)

echo [PASS] ZIP extracted successfully.

echo.
echo ==========================================
echo VALIDATING DEPLOYMENT PACKAGE
echo ==========================================

REM ==================================================
REM Validate Required Files
REM ==================================================

if not exist "%TEMP_DEPLOY%\index.php" (
    echo [ERROR] index.php missing.
    exit /b 1
)

if not exist "%TEMP_DEPLOY%\vendor\autoload.php" (
    echo [ERROR] vendor\autoload.php missing.
    exit /b 1
)

if not exist "%TEMP_DEPLOY%\classes" (
    echo [ERROR] classes folder missing.
    exit /b 1
)

if not exist "%TEMP_DEPLOY%\includes" (
    echo [ERROR] includes folder missing.
    exit /b 1
)

if not exist "%TEMP_DEPLOY%\modules" (
    echo [ERROR] modules folder missing.
    exit /b 1
)

echo.
echo [PASS] Deployment package validation successful.

echo.
echo ==========================================
echo DEPLOYMENT VALIDATION COMPLETED
echo ==========================================

exit /b 0