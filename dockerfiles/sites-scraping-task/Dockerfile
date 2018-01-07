# docker build -t sites-scraping-task -f dockerfiles/sites-scraping-task/Dockerfile .
# docker run --rm -v /home/ubuntu/repos/private/tmp:/var/www/html/tmp -e SITE_URL='https://google.com' sites-scraping-task
# docker run --rm -v /home/ubuntu/repos/private/tmp:/var/www/html/tmp -e SITE_URL='https://google.com' -d sites-scraping-task
# docker run --rm -v /home/ubuntu/repos/private/tmp:/var/www/html/tmp -e SITE_URL='https://google.com' -it sites-scraping-task /bin/bash

FROM ubuntu:latest
MAINTAINER alex@nikitin.ninja <alex@nikitin.ninja>

RUN apt-get update && \
	apt-get -y upgrade && \
	apt-get -y install \
	# apache2 \
	# php7.0 \
	# php7.0-mysql \
	# php7.0-xml \
	# php-pgsql \
	# php-curl \
	# php-xml \
	# php-mbstring \
	# php-zip \
	# libapache2-mod-php7.0 \
	# curl \
	# sshpass \
	# unzip
	php7.0-cli \
	php7.0-dom \
	php7.0-xml \
	imagemagick \
	curl \
	jq \
	phantomjs \
	xvfb



RUN rm -rf /var/www/html/
RUN mkdir -p /var/www/html/
RUN mkdir -p /var/www/html/tmp
RUN chmod 777 /var/www/html/tmp

# Copy this folder to the project file
ADD . /var/www/html/

# Api tasks specific:
CMD cd /var/www/html/ && \
	./entryPoint.sh