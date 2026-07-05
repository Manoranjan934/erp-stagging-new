@echo off
setlocal

echo ==========================================
echo VERIFY DEPLOYMENT
echo ==========================================

set "DEPLOY_PATH=D:\Xampp-org\htdocs\erp-stagging-new"

echo.

if not exist "%DEPLOY_PATH%\index.php" (
    echo [ERROR] index.php not found.
    exit /b 1
)

if not exist "%DEPLOY_PATH%\vendor\autoload.php" (
    echo [ERROR] vendor\autoload.php missing.
    exit /b 1
)

echo [PASS] Deployment verified successfully.

exit /b 0