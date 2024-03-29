#!/bin/bash

pkill inotifywait

inotifywait -r -e modify,close_write,moved_to,create -m /var/www |
grep '\.php$' --line-buffered |
grep -v '^/var/www/vendor/' --line-buffered |
while read -r directory events filename; do
  echo "${events}: ${directory}${filename}"
  rr -c /etc/roadrunner/.rr.yaml http:reset
done