#!/bin/bash

xvfb-run --auto-servernum phantomjs rasterize.js "https://google.com/" "/var/www/html/tmp/google.pdf" "letter"

