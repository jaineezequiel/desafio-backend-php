FROM alpine:3.4

ADD . /

COPY ./config/web.php ./config/web.php

COPY . /var/www/html

# Let docker create a volume for the session dir.
# This keeps the session files even if the container is rebuilt.
VOLUME /var/www/html/var/sessions
