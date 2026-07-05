@echo off
setlocal

echo ==========================================
echo ENTERPRISE DEPLOYMENT - PHASE 1
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
REM Verify ZIP Exists
REM ==========================================

if not exist "%ZIP_FILE%" (
    echo [ERROR] Artifact ZIP not found.
    exit /b 1
)

echo [SUCCESS] Artifact ZIP found.

REM ==========================================
REM Clean Temporary Folder
REM ==========================================

echo.
echo Cleaning temporary deployment folder...

if exist "%TEMP_DEPLOY%" (
    rmdir /s /q "%TEMP_DEPLOY%"
)

mkdir "%TEMP_DEPLOY%"

echo [SUCCESS] Temporary folder prepared.

REM ==========================================
REM Extract ZIP
REM ==========================================

echo.
echo Extracting deployment package...

powershell -Command "Expand-Archive -Path '%ZIP_FILE%' -DestinationPath '%TEMP_DEPLOY%' -Force"

if errorlevel 1 (
    echo [ERROR] ZIP extraction failed.
    exit /b 1
)

echo [SUCCESS] ZIP extracted successfully.

echo.
echo ==========================================
echo DEPLOYMENT PHASE 1 COMPLETED
echo ==========================================

exit /b 0