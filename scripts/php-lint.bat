@echo off
setlocal

echo ================================
echo PHP LINT VALIDATION
echo ================================

for /R %%F in (*.php) do (

    php -l "%%F"

    if errorlevel 1 (

        echo.
        echo ERROR FOUND:
        echo %%F

        exit /b 1

    )

)

echo.
echo PHP Validation Successful.

exit /b 0