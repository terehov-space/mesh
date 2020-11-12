# 1 step
```
docker-compose build app
```

# 2 step
```
docker-compose up -d
```

# 3 step
Init database
```
docker-compose exec app php artisan migrate
```

# 4 step
Init supervisor
```
docker-compose exec app /bin/bash service supervisor start
```