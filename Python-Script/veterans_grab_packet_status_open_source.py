import requests
import glob
import os
import time

if __name__ == "__main__":

    api_key = "HOWEVER_YOU_STORE_AND_GRAB_IT"

    for veteran_text_file in glob.iglob(r'\\PATH_TO_VETERANS_IN_CIRCULATION_FOLDER\*\*txt', recursive=True):

        try:

            veteran_directory_name = os.path.dirname(veteran_text_file)
            veteran_text_file = os.path.basename(veteran_text_file)
            veteran_text_file_split = veteran_text_file.split("_")

            veteran_guid = veteran_text_file_split[0]
            current_status = veteran_text_file_split[1]

            if current_status != "VBMS":
            
                veteran_url_for_status = f"https://sandbox-api.va.gov/services/vba_documents/v1/uploads/{veteran_guid}/"

                r = requests.get(veteran_url_for_status, headers={'apiKey': api_key,'Accept': 'application/json'})

                if r.status_code == 200:
                    veteran_response_json = r.json()
                    veteran_guid_status = veteran_response_json['data']['attributes']['status']
                    veteran_guid_status = veteran_guid_status.upper()
                    os.rename(veteran_directory_name + "\\" + veteran_text_file, veteran_directory_name + "\\" + veteran_guid + "_" + veteran_guid_status + ".txt")

                    ##Consider adding IF Statement here to move VBMS files to a success folder so the script isn't looping through these, if you plan on testing A LOT.
                    
                time.sleep(5) ##SLEEP FOR THROTTLING
        except:
            continue ## REAL ERROR HANDLING CAN GO HERE. IF YOU WANT TO WRITE TO A LOG, ETC

