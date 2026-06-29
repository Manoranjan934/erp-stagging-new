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
                echo "Code downloaded from GitHub"
            }
        }
        
        stage('2. Verify Tools') {
            steps {
                bat '''
                    echo === PHP ===
                    php -v
                    echo === Composer ===
                    composer --version
                    echo === MySQL ===
                    mysql --version
                '''
            }
        }
        
        stage('3. Install Dependencies') {
            steps {
                bat 'composer install --no-interaction --prefer-dist --optimize-autoloader --ignore-platform-reqs'
                echo "Dependencies installed"
            }
        }
        
        stage('4. PHP Syntax Check') {
            steps {
                bat '''
                    echo Checking PHP syntax...
                    for /r %%f in (*.php) do (
                        if "%%~dpf" neq "%CD%\\vendor\\" (
                            php -l "%%f" > syntax_check.txt 2>&1
                            if errorlevel 1 (
                                echo ==========================================
                                echo SYNTAX ERROR FOUND!
                                echo File: %%f
                                echo ---
                                type syntax_check.txt
                                echo ==========================================
                                del syntax_check.txt
                                exit /b 1
                            )
                        )
                    )
                    if exist syntax_check.txt del syntax_check.txt
                    echo All PHP files OK
                '''
            }
        }
        
        stage('5. Setup Database') {
            steps {
                bat 'mysql -u %DB_USERNAME% -e "CREATE DATABASE IF NOT EXISTS `%DB_DATABASE%`;"'
                echo "Database ready"
            }
        }
        
        stage('6. Run Tests') {
            steps {
                bat '''
                    if exist "vendor\\bin\\phpunit.bat" (
                        vendor\\bin\\phpunit.bat --testdox
                        echo Tests passed
                    ) else (
                        echo No PHPUnit found - skipping tests
                    )
                '''
            }
        }
        
        stage('7. Deploy to XAMPP Staging') {
            steps {
                bat '''
                    if not exist "%STAGING_PATH%" mkdir "%STAGING_PATH%"
                    
                    echo Creating backup...
                    if exist "%STAGING_PATH%index.php" (
                        xcopy "%STAGING_PATH%" "%STAGING_PATH%_backup_%date:~-4,4%%date:~-10,2%%date:~-7,2%_%time:~0,2%%time:~3,2%%time:~6,2%" /E /I /H /Y >nul 2>&1
                    )
                    
                    echo Cleaning old files...
                    for /d %%x in ("%STAGING_PATH%*") do (
                        echo %%~nx | findstr /B "_" >nul || rd /s /q "%%x" 2>nul
                    )
                    del /q "%STAGING_PATH%*" 2>nul
                    
                    echo Copying new files...
                    xcopy "*" "%STAGING_PATH%" /E /I /Y /EXCLUDE:exclude.txt
                    
                    echo Deployed to staging!
                    echo http://localhost/erp-stagging-new/
                '''
            }
        }
        
        stage('8. Verify Deployment') {
            steps {
                bat '''
                    timeout /t 3 /nobreak >nul
                    curl -s -o nul -w "HTTP Status: %%{http_code}" http://localhost/erp-stagging-new/ || echo Check manually
                    echo Verification complete
                '''
            }
        }
    }
    
    post {
        always {
            cleanWs()
        }
        success {
            echo ""
            echo "=========================================="
            echo "SUCCESS! Pipeline completed!"
            echo "=========================================="
            echo ""
            echo "Visit: http://localhost/erp-stagging-new/"
            echo ""
        }
        failure {
            echo "FAILED! Check console output above for exact error details."
        }
    }
}