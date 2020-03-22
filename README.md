# Imgur API Upload Image
    Image Upload with imgur API add use this anywhere with session

# Step-1: Change client ID
    Create account from imgur api and collect client ID from imgur web site.
    Go to file custom-cupload.php and change below like as:
         
    // Example
        new Imgur({
            clientid: 'YOUR CLIENT ID', //You can change this ClientID
            callback: feedback
        });

    // Example
        new Imgur({
            clientid: '1d2af99f5v804fb', //You can change this ClientID
            callback: feedback
        });

# Processing
    First user can upload image from custom-upload.php then confirm with submit button then this image link create seesion with php and store this custom-processing-upload.php and auto redirect to custom-finished-upload.php for complete session creating. Then use from this page and do any thing from finished page.

# END
Thank you,
Al Mamun (asifulmamun)