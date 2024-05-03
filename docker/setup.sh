#!/bin/bash

set -xe

docker-compose exec app chown www-data:www-data -R local/storage
