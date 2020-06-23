# Veterans Lighthouse API Benefits Intake Example

### About Cabarrus County
---
Cabarrus is an ever-growing county in the southcentral area of North Carolina. Cabarrus is part of the Charlotte/Concord/Gastonia NC-SC Metropolitan Statistical Area and has a population of about 210,000. Cabarrus is known for its rich stock car racing history and is home to Reed Gold Mine, the site of the first documented commercial gold find in the United States.

### About our team
---
The Business & Location Innovative Services (BLIS) team for Cabarrus County consists of five members:

+ Joseph Battinelli - Team Supervisor
+ Mark McIntyre - Software Developer
+ Landon Patterson - Software Developer
+ Brittany Yoder - Software Developer
+ Marci Jones - Software Developer

Our team is responsible for software development and support for the [County](https://www.cabarruscounty.us/departments/information-technology). We work under the direction of the Chief Information Officer.

### About the VA Lighthouse API
---
The VA Lighthouse API is a way for Local Governments and Veteran Services Offices to digitally upload as opposed to other options, such as fax. The API also allows for better status tracking in real time as uploaded packets make their way through the system.

The documentation for interacting with the API can be found [here](https://developer.va.gov/explore/benefits/docs/benefits?version=current)

We recently rolled out integration software with the VA Lighthouse API(Benefits Intake) for our VSO and wanted to share an Open Source example of how to integrate with the VA API.

### How this setup is different from our setup
---
This setup is somewhat different than our internal application, here are the main differences

+ This setup uses a minimal CSS framework called Spectre CSS and Vanilla Javascript on the front end and PHP on the back end. Our setup uses VueJS and Vuetify on the frontend, and PHP on the back end.
+ This setup uses Python to grab the packet statuses. Our implementation uses Laserfiche and Laserfiche workflow to grab the statuses every 2 hours and web access gives the end user a view into Laserfiche.
+ This setup uses text files for status change tracking, our implementation uses a database.
+ Our form input is validated according to the specs under the VA Lighthouse Documentation.

The reason for these differences is simple, We wanted to build a lightweight, open source application that you could pull down, throw on a server with PHP/Python installed, and start experimenting with right away. **THIS IS NOT MEANT TO BE A PRODUCTION APP**, but rather, a way for you to lay the ground work FOR a production app that gives you lightweight examples of interacting with VA Lighthouse API.

### Getting started
---
To get started using this application, you'll need ensure a few things.

1. Be sure that you have a web server that has PHP installed. If you want to pull statuses back after upload, be sure Python is installed as well.
2. Be sure that PHP cURL has been properly setup and configured to handle HTTPS calls.
3. Be sure you have a secure method setup for retrieving/storing your API key. Most of these examples you can plug and play for dev/test purposes, but please take note of the warning at the bottom.
4. Be sure the Python scripts point to the path that "FILES_IN_CIRCULATION" exists.
5. This application uses two Javascript libraries, Axios for handling the AJAX transactions and DOMPurify for extra protection client side. Both are included in the bundle and nothing extra needs to be done.

### Front end
---
Once you pull down all the files you will see an index.html file. The index.html file is the main front end file and is setup as a single page application with two pages(div tags). The first div tag is a file uploader. The second div tag is a table that has all GUIDS and statuses in circulation contained in a table.

The first page, as mentioned,is uploading a file to the VA. The front end for this is a form with a main file upload and the ability to create attachment files via the "Add Attachment Button". We do validate all file types to ensure PDFs are being uploaded, but ultimately, pattern matching for fields is left to your discretion. For more information on input validation in the API, please review to the VA Lighthouse API documentation.

The second page is a table that is generated in Javascript by data recieved from a back end web service. It contains the Veteran's Name, GUID, and Status for each packet uploaded and in circulation at the VA.


### Back end web services
---
UPLOAD_FILE_TO_VA.php Web Service - This web service is the POST web service for the first page(Veteran upload). The front end transaction on submit performs a POST to this backend web service that contains all necessary files and metadata. This backend Web service then POSTS to the VA initially via PHP cURL to get the PUT location for the file uploads. Upon recieving the PUT location, it then loops through each uploaded file using curl_create_file and pushes them to an array in the naming conventions used by the VA Lighthouse API. Upon receiving a success message, it "touches" a file in veteran's directory in this format...GUID_STATUS.txt, so for example, c49b0419-c0a0-4e3b-89f4-d58e99f0d47d_UPLOADED.txt. In a production app, this would be the equivalent of a database.

GRAB_VETERAN_STATUSES.php Web Service - This web service loops through all the files and directories, grabs the current status of each VA upload using glob, and renders them to a front end table.

### Python Scripts
---
The Python scripts provided pulls statuses back from the VA API. It can be executed manually or put on a scheduled task. We provide scripts for both methods the VA allows. We don't actually USE the Python scripts at Cabarrus County. We use Laserfiche Workflow to pull the statuses back. Not everyone HAS Laserfiche, so instead Python provides a quick and easy way using the requests library to grab the statuses back of all packets in circulation.

The first way is to get each status back for each packet is do so one packet at a time. This is the method we use at Cabarrus County(Because we use Laserfiche Workflow) and our volume is so low(We may have 10-15 packets in circulation at any given time). This method is accomplished by using this URL https://sandbox-api.va.gov/services/vba_documents/v1/uploads/GUID-GOES-HERE. This will return back only the status for the particular GUID requested.

The second way, which is great for higher volume VSOs, is the bulk status method. Using this method you pass a list of GUIDS in ONE call to URL https://sandbox-api.va.gov/services/vba_documents/v1/uploads/report. The GUIDS are sent in JSON format.


### Storing your API key
---
These scripts makes no assumptions about HOW YOU store your API key. For these examples you can plug and play, but please note, whenever you go live, the VA Lighthouse API team WILL want you to demo your app. **If your API key is not stored SAFELY AND SECURE THEY WILL NOT GIVE YOU ACCESS TO PRODUCTION.***

### Links to resources used in this project

1. Spectre CSS- https://picturepan2.github.io/spectre/
2. Axios- https://github.com/axios/axios
3. DOMPurify- https://github.com/cure53/DOMPurify

### Collaboration
---
If you would like to know more about our actual production app and how we have everything setup to work on VueJS/PHP/Laserfiche stack, please reach out to us, we are always happy to collaborate with other Government agencies! 
