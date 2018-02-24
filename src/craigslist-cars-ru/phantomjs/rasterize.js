"use strict";
var page = require('webpage').create(),
    system = require('system'),
    fs = require('fs'),
    address, output, size, pageWidth, pageHeight;

// page.customHeaders = { 'Authorization': 'Basic ' + btoa('scorecard:data!') };

if (system.args.length < 3 || system.args.length > 5) {
    // console.log('Usage: rasterize.js URL filename [paperwidth*paperheight|paperformat] [zoom]');
    // console.log('  paper (pdf output) examples: "5in*7.5in", "10cm*20cm", "A4", "Letter"');
    // console.log('  image (png/jpg output) examples: "1920px" entire page, window width 1920px');
    // console.log('                                   "800px*600px" window, clipped to 800x600');
    console.log('failed');
    phantom.exit(1);
} else {
    address = system.args[1];
    output = system.args[2];

    if (system.args.length > 3 && system.args[2].substr(-4) === ".pdf") {
        size = system.args[3].split('*');

        page.paperSize =
            size.length === 2 ? {
                width: size[0],
                height: size[1],
                margin: '0px'
            } : {
                format: system.args[3],
                orientation: 'landscape',
                margin: {
                    left: '0px',
                    top: '0px',
                    right: '0px',
                    bottom: '0px',
                }
            };

    } else if (system.args.length > 3 && system.args[3].substr(-2) === "px") {
        size = system.args[3].split('*');
        if (size.length === 2) {
            pageWidth = parseInt(size[0], 10);
            pageHeight = parseInt(size[1], 10);
            page.viewportSize = { width: pageWidth, height: pageHeight };
            page.clipRect = { top: 0, left: 0, width: pageWidth, height: pageHeight };
        } else {
            // console.log("size:", system.args[3]);
            pageWidth = parseInt(system.args[3], 10);
            pageHeight = parseInt(pageWidth * 3 / 4, 10); // it's as good an assumption as any
            // console.log("pageHeight:", pageHeight);
            page.viewportSize = {
                width: pageWidth,
                height: pageHeight
            };
        }
    }
    if (system.args.length > 4) {
        page.zoomFactor = system.args[4];
    }

    page.settings.userAgent = 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:58.0) Gecko/20100101 Firefox/58.0';

    var cookiesLocation = fs.workingDirectory + "/src/craigslist-cars-ru/phantomjs/cookies.json";
    var storedCookies = [];
    if (fs.exists(cookiesLocation)) {
        storedCookies = fs.read(cookiesLocation);
        storedCookies = JSON.parse(storedCookies);
    }

    for (var k in storedCookies) {
        page.addCookie(storedCookies[k]);
    }

    console.log('cookies handled');
    console.log( JSON.stringify(storedCookies) );

    page.open(address, function(status) {
        console.log("address: ");
        console.log(address);
        console.log("\n");
        if (status !== 'success') {
            console.log('failed');
            phantom.exit(1);
        } else {
            window.setTimeout(function() {

                // works! - whole content - v1
                // var contentDom = page.evaluate(function() {
                //     var s = new XMLSerializer();
                //     return s.serializeToString(document);
                // });
                // console.log(contentDom);
                // fs.write(output + ".txt", contentDom, 'w');

                // works! - whole content - v2
                // var content = page.content;
                // console.log(content);
                // fs.write(output + ".txt", content, 'w');

                // works! - only necessary css selectors elements by "querySelectorAll"
                var resultRows = page.evaluate(function() {
                    var r = [],
                        elements = document.querySelectorAll('.listing-list .listing__row');
                    for (var i = 0; i < elements.length; i++) {
                        r.push(elements[i].outerHTML);
                    }
                    return r;
                });
                fs.write(output + ".json", JSON.stringify(resultRows), 'w');

                // render page as pdf on letter
                page.render(output);

                fs.write(cookiesLocation, JSON.stringify(page.cookies), 'w');

                console.log('success');
                phantom.exit();
            }, 3000);
        }
    });
}