#!/bin/bash

echo "🚀 Deploying Laravel Pakar Jurusan..."

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Check if Docker is installed
if ! command -v docker &> /dev/null; then
    print_error "Docker is not installed. Please install Docker first."
    exit 1
fi

# Check if Docker Compose is installed
if ! command -v docker compose &> /dev/null; then
    print_error "Docker Compose is not installed. Please install Docker Compose first."
    exit 1
fi

# Create necessary directories
print_status "Creating necessary directories..."
mkdir -p docker
mkdir -p storage/logs
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p bootstrap/cache

# Rename database file if it has (1) in the name
if [ -f "presensi.sql" ]; then
    print_status "Renaming database file..."
    mv "presensi.sql" "presensi.sql"
fi

# Check if database file exists
if [ ! -f "presensi.sql" ]; then
    print_error "Database file presensi.sql not found!"
    exit 1
fi

# Copy environment file
if [ ! -f ".env" ]; then
    print_status "Creating .env file from .env.production..."
    cp .env.production .env
    print_warning "Please update the APP_KEY in .env file"
    print_warning "Run 'php artisan key:generate' locally and update the .env file"
fi

# Stop existing containers
print_status "Stopping existing containers..."
docker compose down

# Build and start containers
print_status "Building and starting containers..."
docker compose up -d --build

# Wait for MySQL to be ready
print_status "Waiting for MySQL to be ready..."
sleep 30

# Check if containers are running
print_status "Checking container status..."
docker compose ps

# Run Laravel commands inside the container
print_status "Running Laravel setup commands..."

# Generate application key if not set
docker compose exec app php artisan key:generate --force

# Run database migrations
docker compose exec app php artisan migrate --force

# Clear and cache configuration
docker compose exec app php artisan config:clear
docker compose exec app php artisan config:cache
docker compose exec app php artisan route:cache
docker compose exec app php artisan view:cache

# Set proper permissions
docker compose exec app chown -R www-data:www-data /var/www/html/storage
docker compose exec app chown -R www-data:www-data /var/www/html/bootstrap/cache
docker compose exec app chmod -R 775 /var/www/html/storage
docker compose exec app chmod -R 775 /var/www/html/bootstrap/cache

print_status "✅ Deployment completed successfully!"
echo ""
print_status "🌐 Your application is now running at:"
print_status "   - Main App: http://$(hostname -I | awk '{print $1}'):9099"
print_status "   - PhpMyAdmin: http://$(hostname -I | awk '{print $1}'):9010"
echo ""
print_status "📊 Database Information:"
print_status "   - Host: mysql (internal), $(hostname -I | awk '{print $1}'):3310 (external)"
print_status "   - Database: smartagenda_db"
print_status "   - Username: laravel_user"  
print_status "   - Password: laravel_password"
echo ""
print_status "🔧 Useful commands:"
print_status "   - View logs: docker compose logs -f"
print_status "   - Stop application: docker compose down"
print_status "   - Restart application: docker compose restart"
print_status "   - Access container: docker compose exec app bash"