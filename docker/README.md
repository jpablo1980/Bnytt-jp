## How to setup your local env using docker

Current working directory of your terminal should be root of this repo
```
# Step 1. Build and start apps
docker-compose up --build -d

# Step 2. Composer install and migrations
./docker/setup.sh

# Done! Live now on http://localhost:3300
```

| Service   | URL                   |
|-----------|-----------------------|
| App       | http://localhost:3300 |
| DB Admin  | http://localhost:3301 |
| MySQL     | tcp://localhost:3302  |

```
# To watch all the logs
docker-compose logs -f

# To shut it down, run:
docker-comopse down

# To start it again
docker-compose up -d

# Example: Run artisan commands
docker-compose exec app php artisan
```
