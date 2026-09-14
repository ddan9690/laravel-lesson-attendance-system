#!/bin/sh
set -eu

prepare_storage() {
    mkdir -p \
        /app/storage/app/public \
        /app/storage/framework/cache/data \
        /app/storage/framework/sessions \
        /app/storage/framework/views \
        /app/storage/framework/testing \
        /app/storage/logs \
        /app/bootstrap/cache

    ln -sfn /app/storage/app/public /app/public/storage
}

is_web() {
    case "${1:-}" in
        --*|frankenphp)
            return 0
            ;;
    esac

    return 1
}

run_as_app() {
    if [ "$(id -u)" = "0" ]; then
        runuser -u www-data -- "$@"
    else
        "$@"
    fi
}

prepare_storage

if [ "$(id -u)" = "0" ]; then
    chown -R www-data:www-data /app/storage /app/bootstrap/cache /config /data
fi

if [ -n "${APP_KEY:-}" ]; then
    should_migrate="${RUN_MIGRATIONS:-false}"

    if is_web "$@"; then
        should_migrate="${RUN_MIGRATIONS:-true}"
    fi

    if [ "${should_migrate}" = "true" ]; then
        run_as_app php artisan migrate --force --no-interaction
    fi

    if [ "${RUN_OPTIMIZE:-true}" = "true" ]; then
        run_as_app php artisan config:cache --no-interaction
    fi

    if is_web "$@"; then
        run_as_app php artisan storage:link --force --no-interaction || true
    fi
else
    echo "Warning: APP_KEY is not set; skipping artisan bootstrap." >&2
fi

if [ "$(id -u)" = "0" ]; then
    exec runuser -u www-data -- docker-php-entrypoint "$@"
fi

exec docker-php-entrypoint "$@"
