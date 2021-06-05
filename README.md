# Trimania Crawler

The Trimania is a organization that carry out sweepstakes in Santa Catarina state on Sundays and give prizes. 

I created this project to mount a dataset and to make studies in numbers and locations that it would answer questions like **What is the neighboorhood with more winners in Joinville?** or **What were the numbers most selected in last year?**.

## Start

Currently, this project runs only in localhost. To configure in your computer follow the steps below. 

a) Create a `.env` and configure environment variables

```bash
$ cp .env.default .env
```

b) Up the Docker containers

```bash
$ docker-compose up -d
```

c) Installing dependencies

```bash
$ docker-compose exec app composer install
```

## Verify the containers

```bash
$ docker ps

CONTAINER ID     IMAGE                   ...   NAMES     
3efe06f79d2e     trimania-crawler_app    ...   trimania_app  
f1bbddbd9820     trimania-crawler_db     ...   trimania_db
```

## Examples

To import or export data is necessary a __sweepstakes date__. Without this information will not possible run these functions. The date format is ```YYYY-MM-DD```.  

### Importing

To import data you need to run a command like below.
```bash
docker-compose exec app php console trimania:import --draw_date=2020-02-02
```
After the importation is showed the numbers and locations that were getting.

![Data Imported](imported.png)

### Exporting

To export data you need to run a command like below.

```bash
docker-compose exec app php console trimania:export --date_begin=2020-01-01 --date_until=2020-01-31 --type=numbers
docker-compose exec app php console trimania:export --date_begin=2020-01-01 --date_until=2020-01-31 --type=locations
```

The files will be gererated in `data_csv` directory.


### Tests

```bash
 docker-compose exec app ./vendor/bin/phpunit tests 
```