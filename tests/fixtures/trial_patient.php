<?php

return array(
    'trial_patient_1' => array(
        'external_trial_identifier' => '12345',
        'trial_id' => $this->getRecord('trial', 'trial1')->id,
        'patient_id' => $this->getRecord('patient', 'patient1')->id,
        'patient_status' => TrialPatient::STATUS_SHORTLISTED,
    ),
    'trial_patient_2' => array(
        'external_trial_identifier' => 'qwerty',
        'trial_id' => $this->getRecord('trial', 'trial1')->id,
        'patient_id' => $this->getRecord('patient', 'patient2')->id,
        'patient_status' => TrialPatient::STATUS_SHORTLISTED,
    ),


    'trial_patient_3' => array(
        'external_trial_identifier' => 'dvorak',
        'trial_id' => $this->getRecord('trial', 'trial2')->id,
        'patient_id' => $this->getRecord('patient', 'patient3')->id,
        'patient_status' => TrialPatient::STATUS_ACCEPTED,
    ),
);
