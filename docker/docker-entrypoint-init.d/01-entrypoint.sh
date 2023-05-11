#!/bin/bash

# Make sure to chmod a+x on this file so it'll run during Bitnami's setup.

set -e
 
role=${CONTAINER_ROLE:-app}
env=${APP_ENV:-production}
 
if [ "$env" != "local" ]; then
    echo "Caching configuration..."
    #(cd /var/www/html && php artisan config:cache && php artisan route:cache && php artisan view:cache)
fi
 
if [ "$role" = "app" ]; then
    echo "Running openswoole..."
    # Run only once

    # Run the application in the background to avoid blocking bitnami execution.
    nohup php artisan octane:start --host=0.0.0.0 --server=swoole --port=8089 &
 
elif [ "$role" = "queue" ]; then
    echo "Running the queue..."

    php /var/www/html/artisan queue:work --verbose --tries=3 --timeout=90
 
elif [ "$role" = "scheduler" ]; then
    while [ true ]
    do
      php /var/www/html/artisan schedule:run --verbose --no-interaction &
      sleep 60
    done
 
else
    echo "Could not match the container role \"$role\""
    exit 1
fi