#!/bin/bash

URLS="https://losangeles.craigslist.org/d/web-html-info-design/search/web?postal=91065&search_distance=50
https://losangeles.craigslist.org/d/systems-networking/search/sad?postal=91065&search_distance=50
https://losangeles.craigslist.org/d/software-qa-dba-etc/search/sof?postal=91065&search_distance=50
https://losangeles.craigslist.org/d/internet-engineering/search/eng?postal=91065&search_distance=50"

rm /var/www/html/tmp/*
for url in $URLS; do
	TST=$(date +'%s')
	SITE_URL=$url
	printf "$SITE_URL" > /var/www/html/tmp/site-url.txt
	printf "$(date)" > /var/www/html/tmp/run-date.txt
	xvfb-run --auto-servernum phantomjs --version > /var/www/html/tmp/phantomjs-version.txt
	xvfb-run --auto-servernum phantomjs src/craigslist-jobs/phantomjs/rasterize.js "$SITE_URL" "/var/www/html/tmp/results$TST.pdf" "letter"
	php src/craigslist-jobs/php/craigslist-parser.php results$TST.pdf > /var/www/html/tmp/results-scraping-$TST.json
	# curl -s -H "Content-Type: application/json" -X POST -d @/var/www/html/tmp/results-scraping-$TST.json https://api.nikitin.ninja/v1/scrapeJobs/storeData
done;










