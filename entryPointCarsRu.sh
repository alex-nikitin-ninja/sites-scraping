#!/bin/bash

URLS="https://auto.ru/zelenograd/cars/all/?transmission_full=AUTO_AUTOMATIC&transmission_full=AUTO_VARIATOR&beaten=1&customs_state=1&km_age_to=40000&price_to=800000&image=true&sort_offers=cr_date-DESC&top_days=off&currency=RUR&output_type=list"
# https://losangeles.craigslist.org/d/systems-networking/search/sad?postal=91065&search_distance=50
# https://losangeles.craigslist.org/d/software-qa-dba-etc/search/sof?postal=91065&search_distance=50
# https://losangeles.craigslist.org/d/internet-engineering/search/eng?postal=91065&search_distance=50"

rm /var/www/html/tmp/*
for url in $URLS; do
	TST=$(date +'%s')
	SITE_URL=$url
	printf "$SITE_URL" > /var/www/html/tmp/$TST-site-url.txt
	printf "$(date)" > /var/www/html/tmp/$TST-run-date.txt
	xvfb-run --auto-servernum phantomjs --version > /var/www/html/tmp/$TST-phantomjs-version.txt
	xvfb-run --auto-servernum phantomjs src/craigslist-cars-ru/phantomjs/rasterize.js "$SITE_URL" "/var/www/html/tmp/$TST-results.pdf" "letter"
	


	# php src/craigslist-jobs/php/craigslist-parser.php $TST-results.pdf > /var/www/html/tmp/$TST-results-scraping.json
	# curl -s -H "Content-Type: application/json" -X POST -d @/var/www/html/tmp/$TST-results-scraping.json https://api.nikitin.ninja/v1/scrapeJobs/storeData
done;










