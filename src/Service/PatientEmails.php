<?php

namespace App\Service;

use App\Repository\AppointmentRepository;

class PatientEmail  
{
    public function patientEmailsbookedInThisDay($doctor ,$date , AppointmentRepository $appRepo ) {
        $apppointments=$appRepo->findbyDoctorIdandDateName($doctor , $date , '=');

        $emails=array();
        foreach($apppointments as $app){
            $emails[]= $app->getPatient()->getEmail();;
        }
        return $emails;
    }


    public function patientsInfoOfThisDay($doctor ,$date , AppointmentRepository $appRepo ) {
        
        $apppointments=$appRepo->findbyDoctorIdandDateName($doctor , $date , '=');

        $patient=new \stdClass();
        $patientArray=array();
        foreach($apppointments as $app){
            $patient->email= $app->getPatient()->getEmail();
            $patient->firstName=$app->getPatient()->getFirstName();
            $patient->lastName=$app->getPatient()->getLastName();
            $patient->id_CalandarDay=$app->getTimeSlot()->getCalandarDay()->getId();
            $patient->id_TimeSlot=$app->getTimeSlot()->getId();
            $patient->id_doctor=$app->getDoctor()->getId();
            $patientArray[]=clone $patient;
        }
        unset($apppointments);
        return $patientArray;
    }
    
}
