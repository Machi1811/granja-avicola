web: php artisan serve --host=0.0.0.0 --port=$PORT
# Scheduler (requiere servicio adicional o cron-job.org externo)
scheduler: while true; do php artisan schedule:run --verbose --no-interaction & sleep 60; done
