<?php

require './db_api.php';

# A function that will update the user's personal information. All fields are optional. Fields that are not null will be updated.


function update_personal_details($user_id, $name =null, $address = null, $zip = null, $city = null, $bday = null){

    # Check which fields are not null.
    $fields = array();
    if(!is_null($name)){
        $fields['name'] = $name;
    }
    if(!is_null($address)){
        $fields['address'] = $address;
    }
    if(!is_null($zip)){
        $fields['zip'] = $zip;
    }
    if(!is_null($city)){
        $fields['city'] = $city;
    }
    if(!is_null($bday)){
        $fields['bday'] = $bday;
    }

    # Whichever fields are not null, update the user's information.
}
