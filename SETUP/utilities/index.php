<?php

function generateReferenceNumber($prefix = '', $length = 8) {
    // Generate a unique ID based on the current time in microseconds
    $unique_id = uniqid($prefix, true);

    // Extract and concatenate specific parts of the unique ID for the reference number
    $reference_number = strtoupper(substr(md5($unique_id), 0, $length));

    return $reference_number;
}

function getElapsedTime($dateTime) {
    // Create DateTime objects for the given date and current time
    if($dateTime == "0000-00-00 00:00:00" || $dateTime == NULL) return '--';

    $new_datetime = DateTime::createFromFormat ( "Y-m-d H:i:s", $dateTime );
    $dateTimeParsed = $new_datetime->format('Y-m-d H:i:s');

    $date = new DateTime($dateTimeParsed);
    $now = new DateTime();
    $interval = $now->diff($date);
    
    // Calculate the total elapsed time in different units
    $seconds = $interval->s;
    $minutes = $interval->i;
    $hours = $interval->h;
    $days = $interval->days;
    $months = $interval->m;
    $years = $interval->y;

    if ($years > 0) {
        return $years . ' year' . ($years > 1 ? 's' : '') . ' ago';
    }
    if ($months > 0) {
        return $months . ' month' . ($months > 1 ? 's' : '') . ' ago';
    }
    if ($days > 0) {
        return $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
    }
    if ($hours > 0) {
        return $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
    }
    if ($minutes > 0) {
        return $minutes . ' minute' . ($minutes > 1 ? 's' : '') . ' ago';
    }
    if ($seconds > 0) {
        return $seconds . ' second' . ($seconds > 1 ? 's' : '') . ' ago';
    }

    return 'a moment ago'; // For cases where the interval is less than a second
}

function dateFromFormat($inputDate, $format = 'Y-m-d') {
    if($inputDate == "0000-00-00 00:00:00" || $inputDate == '' || $inputDate == null) return '--';
    $new_datetime = DateTime::createFromFormat ( 'Y-m-d', $inputDate );
    return $new_datetime->format($format); 
}

function dateTimeFromFormat($inputDate, $format = 'Y-m-d H:i:s') {
    if($inputDate == "0000-00-00 00:00:00"|| $inputDate == null) return '--';
    $new_datetime = DateTime::createFromFormat ( 'Y-m-d H:i:s', $inputDate );
    return $new_datetime->format($format); 
}

function parseSchoolYear($value, $toNumerals = false) {
        
    $nums = ['I' => "1st", 'II' => "2nd", 'III' => "3rd", 'IV' => "4th", 'V' => "5th"];
    $values = [
                '1st year' => "I", 
                '2nd year' => "II", 
                '3rd year' => "III", 
                '4th year' => "IV"
            ];

    if(!$toNumerals){
        if(array_key_exists($value, $nums)){
            return $nums[$value]." year";
        }else{
            return $value;
        }    
    }else if($toNumerals){
        if(array_key_exists($value, $values)){
            return $values[$value];
        }else{
            return $value;
        }
    }
    
}

function verifyEmailAddress($email) {
    if(filter_var($email, FILTER_VALIDATE_EMAIL)){
        
        if(str_contains($email, "@g.batstate-u.edu.ph")){
            return true;
        }else{
            return false;    
        }

    }else{
        return false;
    }
}

?>