import requests
import glob
import os
import time

if __name__ == "__main__":

    ##This isn't the method we use
    ##Demonstrates how to do a bulk call to the Veteran API in Python
    ##Script could probably be cleaned up a bit, but it shows the overall example
    ##Probably needs some error handling at some point

    api_key = "HOWEVER_YOU_STORE_AND_GRAB_IT"

    ids_for_report = []

    ##LOOPS THROUGH VETERANS IN CIRCULATION DIRECTORY, GRABS ALL THE GUIDS FROM THE TEXT FILES
    ##IF NOT VBMS STATUS, PUSH THEM TO THE IDS FOR REPORT
    ##REQUEST DOES A JSON POST PER VA LIGHTHOUSE API STANDARDS
    ##REQUEST RECIEVES A JSON PAYLOAD BACK OF ALL GUID AND THERE STATUS
    ##LOOPS THROUGH ALL STATUSES, FINDS THEIR CORRESPONDING TEXT FILE IN THE DIRECTORY
    ##UPDATES TEXT FILE WITH NEW STATUS

    for veteran_text_file in glob.iglob(r'\\PATH\VETERANS\IN\CIRCULATION\*\*txt', recursive=True):

        try:

            veteran_directory_name = os.path.dirname(veteran_text_file)
            veteran_text_file = os.path.basename(veteran_text_file)
            veteran_text_file_split = veteran_text_file.split("_")

            veteran_guid = veteran_text_file_split[0]
            current_status = veteran_text_file_split[1]

            if current_status != "VBMS":
                ids_for_report.append(veteran_guid)

        except Exception as e:
            print(e)
            continue ## REAL ERROR HANDLING CAN GO HERE. IF YOU WANT TO WRITE TO A LOG, ETC

    veteran_bulk_report_url = "https://sandbox-api.va.gov/services/vba_documents/v1/uploads/report"


    r = requests.post(veteran_bulk_report_url, json={"ids":ids_for_report}, headers={'apiKey': api_key, 'Content-Type': 'application/json'})
    guid_statuses = r.json()
    guid_statuses = guid_statuses["data"]

    r.close()

    for guid_status in guid_statuses:

        guid_for_compare = guid_status["id"]
        guid_updated_status = guid_status["attributes"]["status"].upper()
        new_text_file_name = f"{guid_for_compare}_{guid_updated_status}.txt"

        ##Renames text file to new GUID..REAL LIFE THIS WOULD PROBABLY BE A SQL UPDATE

        for veteran_text_file in glob.iglob(r'\\PATH\TO\VETERANS\IN\CIRCULATION\*\*txt', recursive=True):
            veteran_directory_name = os.path.dirname(veteran_text_file)
            veteran_text_file = os.path.basename(veteran_text_file)

            if guid_for_compare in veteran_text_file:
                os.rename(veteran_directory_name + "\\" + veteran_text_file, veteran_directory_name + "\\" + new_text_file_name)

