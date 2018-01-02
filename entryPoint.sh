#!/bin/bash

xvfb-run --auto-servernum phantomjs rasterize.js "https://google.com/" "google.pdf" "letter"

