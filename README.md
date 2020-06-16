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
The VA Lighthouse API is a way for Local Governments and Veteran Services Offices to upload documents and form data to the VA digitally as opposed to other options, such as fax. The documentation for interacting with the API can be found here. With the API Veteran Services Offices can track the status of the documents uploaded as well.

We recently rolled out integration software with the VA Lighthouse API for our VSO and wanted to share an Open Source example of how to integrate with the VA API.

### How this setup is different from our setup
---
This setup is somewhat different than our internal application, here are the main differences

+ This setup uses a minimal CSS framework called Spectre and Vanilla Javascript on the front end and PHP on the back end. Our setup uses VueJS and Vuetify on the frontend, and PHP on the back end.
+ This setup uses Python to grab the packet statuses. Our implementation uses Laserfiche and Laserfiche workflow to grab the status every 2 hours and web client gives the end user a view into Laserfiche.
+ This setup uses text files for status change tracking, our implementation uses a database.
+ Our form input is validated according to the specs under the VA Lighthouse Documentation.

The reason for these differences is simple, We wanted to build an open source application that you could pull down, throw on a server, and start experimenting with right away and view all the VA API calls and how they work. Obviously you wouldn't use this application in full blown production mode. But more so to give you an example of how to start integrating with the VA Lighthouse API.

### Front end
---
Once you pull down all the files you will se a index.html file. The index.html file is the main front end file and is setup as a single page application with two pages(div tags). The first slot is a file uploader. The second slot is a table that has all GUIDS and statuses in circulation contained in a table.

The front end uses axios.js and AJAX calls the complete it's transactions. 

The first transaction it handles is uploading a file to the VA. The front end for this is a form with a main file upload and the ability to create more file uploads via the "Add Attachment Button". We did leave out input/file validation because different people perfer different methods of validation and may vary depending on frameworks used. For instructions and proper validation for each field, please refer to the VA Lighthouse API documentation.

The second transaction it handles is generating a table that contains all Veteran First Name, GUID, and VA Status.


### Back end web services
---
UPLOAD_FILE_TO_VA.php Web Service - This web service is the POST web service for the first. The front end transaction on submit performs a POST to this backend web service that contains all necessary files and metadata. This backend Web service then POSTS to the VA initially via PHP cURL to get the PUT location for the file uploads. Upon recieving the PUT location, it then loops through each uploaded file using curl_create_file and pushes them to an array in the naming conventions used by the VA Lighthouse API. Upon receiving a success message, it "touches" a file that exists in the veteran's directory in this format...GUID_STATUS.txt, so for example, c49b0419-c0a0-4e3b-89f4-d58e99f0d47d_UPLOADED.txt.

GRAB_VETERAN_STATUSES.php Web Service - This web service loops through all the files and directories, grabs the current status of each VA upload, and renders them to a front end table.

### Python Script
---
The Python script, when ran(Or put on a scheduled task), loops through the directory and pulls the GUID from the text file. It then performs an API call using Python Requests and updates the text file with the new status.

**NOTE** If you are a high volume VSO, the VA also offers another way to do a bulk status grab. We don't use this method because our volume is so low(the most we may have in any given time in circulation is roughly 10-20 packets), so looping through each works just fine for us. If you can't afford to do this due to volume, please check out the bulk status upload as described in the VA Lighthouse API documentation.

### Storing your API key
---
These script makes no assumptions about HOW YOU store your API key. For these examples you can plug and play, but please note, whenever you go live, the VA Lighthouse API team WILL want you to demo your app. **If your API key is not stored SAFELY AND SECURE THEY WILL NOT GIVE YOU ACCESS TO PRODUCTION.***
