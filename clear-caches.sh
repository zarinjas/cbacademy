#!/bin/bash

echo "🧹 Clearing Laravel caches..."

# Clear route cache
php artisan route:clear
echo "✅ Route cache cleared"

# Clear config cache
php artisan config:clear
echo "✅ Config cache cleared"

# Clear view cache
php artisan view:clear
echo "✅ View cache cleared"

# Clear application cache
php artisan cache:clear
echo "✅ Application cache cleared"

# Show routes to verify
echo "🔍 Listing lesson routes:"
php artisan route:list --name=lessons

echo "🎉 All caches cleared successfully!"
echo "💡 You can now test the lesson routes"
