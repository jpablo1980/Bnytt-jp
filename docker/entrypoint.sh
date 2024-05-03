#!/bin/bash

if [ $# -eq 0 ]
  then
    echo "No arguments supplied, starting web server"
else
    while [ ! -f docker/setup-complete ]
    do
        echo "Waiting for setup to complete (Checking if file docker/setup-complete exists)"
        sleep 5
    done
    $@
    exit $?
fi

nginx -g 'daemon on;'
status=$?
if [ $status -ne 0 ]; then
  echo "Failed to start nginx: $status"
  exit $status
fi

php-fpm -D -O
status=$?
if [ $status -ne 0 ]; then
  echo "Failed to start php-fpm: $status"
  exit $status
fi

mkdir public/cache
chown www-data:www-data -R public/cache
chown www-data:www-data -R storage

while sleep 10; do
  ps aux |grep nginx |grep -q -v grep
  if [ $? -ne 0 ]; then
      echo "nginx exited."; exit
  fi

  ps aux |grep "php-fpm" |grep -q -v grep
  if [ $? -ne 0 ]; then
      echo "php-fpm exited."; exit
  fi
done
