# Local Setup

## Step 1 - Clone the Repository

```bash
cd [project_path]
git clone git@github.com:mtasca/spotify_test.git;
```

## Step 2 - Run Composer

assuming you have [Composer](https://getcomposer.org) installed as `composer` (the alternative being `composer.phar`), you can run it using:

```bash
cd [project_path];
composer install -o;
```

## Step 3 - Run and Test the service

Run docker-compose

````
cd [project_path]
docker-compose up
````
Navigate to the following URL and you should get this:

- http://0.0.0.0:8080/service/health

```json
{
  "metadata": {
    "code": 200,
    "message": "OK"
  },
  "data": {
    "isHealthy": true,
    "status": 200,
    "env": "dev"
  }
}
```

