# Game of the life

## How to run

To test this solution you can use docker

```
docker-compose up -d
docker exec -it b_php php game.php [<glider|pulsar>]
```

Or you can use PHP, installed locally.

```
cd src
php game.php [<glider|pulsar>]
```

To stop the simulation use `ctrl + C`
