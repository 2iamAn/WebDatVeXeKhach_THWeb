@echo off
echo ========================================
echo CHUAN BI DEPLOY LEN INFINITYFREE
echo ========================================
echo.

echo [1/5] Dang build assets...
call npm run build
if %errorlevel% neq 0 (
    echo LOI: Build assets that bai!
    pause
    exit /b 1
)
echo [OK] Build assets thanh cong!
echo.

echo [2/5] Dang cai dat dependencies (production)...
call composer install --optimize-autoloader --no-dev
if %errorlevel% neq 0 (
    echo LOI: Cai dat dependencies that bai!
    pause
    exit /b 1
)
echo [OK] Cai dat dependencies thanh cong!
echo.

echo [3/5] Dang cache config...
call php artisan config:cache
if %errorlevel% neq 0 (
    echo CANH BAO: Khong the cache config (co the do chua co .env)
)
echo.

echo [4/5] Dang cache routes...
call php artisan route:cache
if %errorlevel% neq 0 (
    echo CANH BAO: Khong the cache routes
)
echo.

echo [5/5] Dang cache views...
call php artisan view:cache
if %errorlevel% neq 0 (
    echo CANH BAO: Khong the cache views
)
echo.

echo ========================================
echo HOAN TAT! San sang upload len InfinityFree
echo ========================================
echo.
echo CAC BUOC TIEP THEO:
echo 1. Upload toan bo thu muc len htdocs/
echo 2. Tao file .env tu .env.infinitifree
echo 3. Cap nhat APP_URL trong .env
echo 4. Set quyen cho storage/ va bootstrap/cache/
echo 5. Import database
echo.
echo Xem chi tiet trong file DEPLOY_INFINITYFREE.md
echo.
pause
