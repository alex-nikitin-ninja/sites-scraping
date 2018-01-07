#!/bin/bash

rm /var/www/html/tmp/*
SITE_URL="https://losangeles.craigslist.org/search/cta?hasPic=1&search_distance=50&postal=91065&min_price=1050&max_price=8000&min_auto_year=2010&auto_title_status=1"
printf "$SITE_URL" > /var/www/html/tmp/site-url.txt
xvfb-run --auto-servernum phantomjs --version > /var/www/html/tmp/phantomjs-version.txt
xvfb-run --auto-servernum phantomjs src/phantomjs/rasterize.js "$SITE_URL" "/var/www/html/tmp/results.pdf" "letter"
php src/php/craigslist-parser-cars.php > /var/www/html/tmp/results-scraping.txt
