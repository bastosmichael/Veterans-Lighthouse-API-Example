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

### How this setup works
---
This setup is somewhat different than our internal application, here are the main differences

+ This setup uses a minimal CSS framework called Spectre and Vanilla Javascript on the front end and PHP on the back end. Our setup uses VueJS and Vuetify on the frontend, and PHP on the back end.
+ This setup uses Python to grab the packet statuses. Our implementation uses Laserfiche and Laserfiche workflow to grab the status every 2 hours and web client.
+ This setup uses text files for status change tracking, our implementation uses a database.
