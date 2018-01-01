# docker build -t sites-scraping-task -f dockerfiles/sites-scraping-task/Dockerfile .
# docker run -e SITE_URL='https://google.com' -d sites-scraping-task
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
	curl \
	sshpass \
	unzip

RUN a2enmod php7.0
RUN a2enmod rewrite

RUN rm -rf /var/www/html/
RUN mkdir /var/www/html/

# Copy this folder to the project file
ADD . /var/www/html/


# Creating temp directory
RUN mkdir /var/www/html/tmp/
RUN chmod 777 /var/www/html/tmp/

# Api tasks specific:
CMD cd /var/www/html/ && \
	./entryPoint.sh