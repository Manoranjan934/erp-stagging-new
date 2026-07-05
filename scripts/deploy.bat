REM ==========================================
REM Safe Deployment
REM ==========================================

echo.
echo ==========================================
echo DEPLOYING APPLICATION
echo ==========================================

set "DEPLOY_PATH=D:\Xampp-org\htdocs\erp-stagging-new"

echo Deploy Start : %DATE% %TIME%>>logs\deployment.log

echo.
echo Copying verified files...

robocopy "%TEMP_DEPLOY%" "%DEPLOY_PATH%" /E /COPY:DAT /R:2 /W:2 /NFL /NDL

set RC=%ERRORLEVEL%

echo Robocopy Exit Code : %RC%>>logs\deployment.log

if %RC% GEQ 8 (

    echo.
    echo [ERROR] Deployment failed.

    echo Deployment Failed>>logs\deployment.log

    exit /b 1
)

echo.
echo [PASS] Files copied successfully.

echo Deployment Successful>>logs\deployment.log