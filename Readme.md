# 1 step
```
docker-compose build app
```

# 2 step
```
docker-compose up -d
```

# 3 step
enter app container and execute
```
php artisan migrate:fresh
```