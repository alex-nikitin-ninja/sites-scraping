#!/bin/bash

rm /var/www/html/tmp/*
SITE_URL="https://losangeles.craigslist.org/search/cta?hasPic=1&search_distance=50&postal=91065&min_price=1050&max_price=80000&min_auto_year=2000&auto_title_status=1"
printf "$SITE_URL" > /var/www/html/tmp/site-url.txt
printf "$(date)" > /var/www/html/tmp/run-date.txt
xvfb-run --auto-servernum phantomjs --version > /var/www/html/tmp/phantomjs-version.txt
xvfb-run --auto-servernum phantomjs src/craigslist-cars/phantomjs/rasterize.js "$SITE_URL" "/var/www/html/tmp/results.pdf" "letter"
php src/craigslist-cars/php/craigslist-parser-cars.php > /var/www/html/tmp/results-scraping.json
curl -s -H "Content-Type: application/json" -X POST -d @/var/www/html/tmp/results-scraping.json https://api.nikitin.ninja/v1/scrapeCars/storeData

