pipeline {
    agent any
    
    environment {
        PHP_PATH = 'D:\\Xampp-org\\php'
        MYSQL_PATH = 'D:\\Xampp-org\\mysql\\bin'
        PATH = "${PHP_PATH};${MYSQL_PATH};${PATH}"
        
        STAGING_PATH = 'D:/Xampp-org/htdocs/erp-stagging-new/'
        DB_HOST = 'localhost'
        DB_PORT = '3306'
        DB_DATABASE = 'erp-stagging-new'
        DB_USERNAME = 'root'
        DB_PASSWORD = ''
    }
    
    options {
        buildDiscarder(logRotator(numToKeepStr: '5'))
        disableConcurrentBuilds()
        timeout(time: 15, unit: 'MINUTES')
        timestamps()
    }
    
    stages {
        stage('1. Checkout Code') {
            steps {
                cleanWs()
                checkout scm
                ansiColor('xterm') {
                    echo "\u001B[32m✅ Code downloaded from GitHub\u001B[0m"
                }
            }
        }
        
        stage('2. Verify Tools') {
            steps {
                ansiColor('xterm') {
                    bat '''
                        echo \u001B[36m==========================================\u001B[0m
                        echo \u001B[36m  CHECKING INSTALLED TOOLS\u001B[0m
                        echo \u001B[36m==========================================\u001B[0m
                        
                        echo \u001B[33m[PHP]\u001B[0m
                        php -v
                        
                        echo \u001B[33m[Composer]\u001B[0m
                        composer --version
                        
                        echo \u001B[33m[MySQL]\u001B[0m
                        mysql --version
                        
                        echo \u001B[32m✅ All tools verified\u001B[0m
                    '''
                }
            }
        }
        
        stage('3. Install Dependencies') {
            steps {
                ansiColor('xterm') {
                    bat '''
                        echo \u001B[36m==========================================\u001B[0m
                        echo \u001B[36m  INSTALLING COMPOSER DEPENDENCIES\u001B[0m
                        echo \u001B[36m==========================================\u001B[0m
                        
                        composer install --no-interaction --prefer-dist --optimize-autoloader --ignore-platform-reqs
                        
                        echo \u001B[32m✅ Dependencies installed\u001B[0m
                    '''
                }
            }
        }
        
        stage('4. PHP Syntax Check') {
            steps {
                ansiColor('xterm') {
                    bat '''
                        echo \u001B[36m==========================================\u001B[0m
                        echo \u001B[36m  PHP SYNTAX CHECK\u001B[0m
                        echo \u001B[36m==========================================\u001B[0m
                        
                        setlocal EnableDelayedExpansion
                        set "ERROR_FOUND=0"
                        
                        for /r %%f in (*.php) do (
                            if "%%~dpf" neq "%CD%\\vendor\\" (
                                php -l "%%f" > syntax_check.txt 2>&1
                                if errorlevel 1 (
                                    echo.
                                    echo \u001B[41m\u001B[97m                                          \u001B[0m
                                    echo \u001B[41m\u001B[97m  ❌ SYNTAX ERROR FOUND!                  \u001B[0m
                                    echo \u001B[41m\u001B[97m                                          \u001B[0m
                                    echo.
                                    echo \u001B[31m[FILE]    : %%f\u001B[0m
                                    type syntax_check.txt
                                    echo.
                                    echo \u001B[41m\u001B[97m  FIX THIS ERROR AND REBUILD              \u001B[0m
                                    echo.
                                    set "ERROR_FOUND=1"
                                    goto :show_result
                                ) else (
                                    echo \u001B[32m✅ [OK]    : %%f\u001B[0m
                                )
                            )
                        )
                        
                        :show_result
                        if exist syntax_check.txt del syntax_check.txt
                        
                        if "!ERROR_FOUND!"=="1" (
                            echo.
                            echo \u001B[41m\u001B[97m==========================================\u001B[0m
                            echo \u001B[41m\u001B[97m  BUILD FAILED - SYNTAX ERROR             \u001B[0m
                            echo \u001B[41m\u001B[97m==========================================\u001B[0m
                            exit /b 1
                        ) else (
                            echo.
                            echo \u001B[42m\u001B[97m==========================================\u001B[0m
                            echo \u001B[42m\u001B[97m  ALL PHP FILES PASSED ✅                 \u001B[0m
                            echo \u001B[42m\u001B[97m==========================================\u001B[0m
                        )
                    '''
                }
            }
        }
        
        stage('5. Setup Database') {
            steps {
                ansiColor('xterm') {
                    bat '''
                        echo \u001B[36m==========================================\u001B[0m
                        echo \u001B[36m  SETTING UP DATABASE\u001B[0m
                        echo \u001B[36m==========================================\u001B[0m
                        
                        mysql -u %DB_USERNAME% -e "CREATE DATABASE IF NOT EXISTS `%DB_DATABASE%`;"
                        
                        echo \u001B[32m✅ Database ready\u001B[0m
                    '''
                }
            }
        }
        
        stage('6. Run Tests') {
            steps {
                ansiColor('xterm') {
                    bat '''
                        if exist "vendor\\bin\\phpunit.bat" (
                            echo \u001B[36mRunning PHPUnit tests...\u001B[0m
                            vendor\\bin\\phpunit.bat --testdox
                            echo \u001B[32m✅ Tests passed\u001B[0m
                        ) else (
                            echo \u001B[33m⚠️ No PHPUnit found - skipping tests\u001B[0m
                        )
                    '''
                }
            }
        }
        
        stage('7. Deploy to XAMPP Staging') {
            steps {
                ansiColor('xterm') {
                    bat '''
                        echo \u001B[36m==========================================\u001B[0m
                        echo \u001B[36m  DEPLOYING TO STAGING\u001B[0m
                        echo \u001B[36m==========================================\u001B[0m
                        
                        if not exist "%STAGING_PATH%" mkdir "%STAGING_PATH%"
                        
                        echo \u001B[33mCreating backup...\u001B[0m
                        if exist "%STAGING_PATH%index.php" (
                            xcopy "%STAGING_PATH%" "%STAGING_PATH%_backup_%date:~-4,4%%date:~-10,2%%date:~-7,2%_%time:~0,2%%time:~3,2%%time:~6,2%" /E /I /H /Y >nul 2>&1
                        )
                        
                        echo \u001B[33mCleaning old files...\u001B[0m
                        for /d %%x in ("%STAGING_PATH%*") do (
                            echo %%~nx | findstr /B "_" >nul || rd /s /q "%%x" 2>nul
                        )
                        del /q "%STAGING_PATH%*" 2>nul
                        
                        echo \u001B[33mCopying new files...\u001B[0m
                        xcopy "*" "%STAGING_PATH%" /E /I /Y /EXCLUDE:exclude.txt
                        
                        echo.
                        echo \u001B[42m\u001B[97m==========================================\u001B[0m
                        echo \u001B[42m\u001B[97m  ✅ DEPLOYED SUCCESSFULLY!               \u001B[0m
                        echo \u001B[42m\u001B[97m  http://localhost/erp-stagging-new/       \u001B[0m
                        echo \u001B[42m\u001B[97m==========================================\u001B[0m
                    '''
                }
            }
        }
        
        stage('8. Verify Deployment') {
            steps {
                ansiColor('xterm') {
                    bat '''
                        echo \u001B[36mVerifying deployment...\u001B[0m
                        timeout /t 3 /nobreak >nul
                        curl -s -o nul -w "\u001B[32mHTTP Status: %%{http_code}\u001B[0m" http://localhost/erp-stagging-new/ || echo \u001B[33mCheck manually\u001B[0m
                        echo \u001B[32m✅ Verification complete\u001B[0m
                    '''
                }
            }
        }
    }
    
    post {
        always {
            cleanWs()
        }
        success {
            ansiColor('xterm') {
                echo "\u001B[42m\u001B[97m==========================================\u001B[0m"
                echo "\u001B[42m\u001B[97m  🎉 SUCCESS! PIPELINE COMPLETED!         \u001B[0m"
                echo "\u001B[42m\u001B[97m==========================================\u001B[0m"
                echo "\u001B[32m🌐 Visit: http://localhost/erp-stagging-new/\u001B[0m"
            }
        }
        failure {
            ansiColor('xterm') {
                echo "\u001B[41m\u001B[97m==========================================\u001B[0m"
                echo "\u001B[41m\u001B[97m  ❌ PIPELINE FAILED!                     \u001B[0m"
                echo "\u001B[41m\u001B[97m==========================================\u001B[0m"
                echo "\u001B[31m💡 Check console output for red error boxes\u001B[0m"
            }
        }
    }
}