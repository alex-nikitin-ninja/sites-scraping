#!/bin/bash

URLS="https://auto.ru/zelenograd/cars/used/?beaten=1&customs_state=1&geo_id=216&geo_radius=100&km_age_to=80000&price_to=2500000&image=true&sort_offers=cr_date-DESC&top_days=off&currency=RUR&output_type=list&page_num_offers=1
https://auto.ru/zelenograd/cars/used/?beaten=1&customs_state=1&geo_id=216&geo_radius=100&km_age_to=80000&price_to=2500000&image=true&sort_offers=cr_date-DESC&top_days=off&currency=RUR&output_type=list&page_num_offers=2
https://auto.ru/zelenograd/cars/used/?beaten=1&customs_state=1&geo_id=216&geo_radius=100&km_age_to=80000&price_to=2500000&image=true&sort_offers=cr_date-DESC&top_days=off&currency=RUR&output_type=list&page_num_offers=3"

rm /var/www/html/tmp/*
for url in $URLS; do
	TST=$(date +'%s')
	SITE_URL=$url
	printf "$SITE_URL" > /var/www/html/tmp/$TST-site-url.txt
	printf "$(date)" > /var/www/html/tmp/$TST-run-date.txt
	xvfb-run --auto-servernum phantomjs --version > /var/www/html/tmp/$TST-phantomjs-version.txt
	xvfb-run --auto-servernum phantomjs src/craigslist-cars-ru/phantomjs/rasterize.js "$SITE_URL" "/var/www/html/tmp/$TST-results.pdf" "letter"
	php src/craigslist-cars-ru/php/autoru-parser.php $TST-results.pdf > /var/www/html/tmp/$TST-results-scraping.json
	curl -s -H "Content-Type: application/json" -X POST -d @/var/www/html/tmp/$TST-results-scraping.json https://api.nikitin.ninja/v1/scrapeCars/storeDataRu

	sleep 15
done;










