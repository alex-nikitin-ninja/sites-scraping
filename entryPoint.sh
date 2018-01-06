#!/bin/bash

xvfb-run --auto-servernum phantomjs src/rasterize.js "https://google.com/" "/var/www/html/tmp/google.pdf" "letter"

