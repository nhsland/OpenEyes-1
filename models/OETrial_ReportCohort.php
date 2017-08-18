<?php

/**
 * Class OETrial_ReportCohort
 */
class OETrial_ReportCohort extends BaseReport
{
    /**
     * @var int The ID of the trial
     */
    public $trialID;
    /**
     * @var TrialPatient[] The patients for the trial
     */
    public $patients = array();

    /**
     * @return array
     */
    public function rules()
    {
        return array(
            array('trialID', 'safe'),
        );
    }

    /**
     * @return CDbCommand The search command
     */
    public function getDbCommand()
    {
        return Yii::app()->db->createCommand()
            ->from('trial t')
            ->join('trial_patient t_p', 't.id = t_p.trial_id')
            ->join('patient p', 'p.id = t_p.patient_id')
            ->join('contact c', 'p.contact_id = c.id')
            ->leftJoin('medication m', 'p.id = m.patient_id')
            ->leftJoin('drug d', 'm.drug_id = d.id')
            ->leftJoin('secondary_diagnosis sd', 'sd.patient_id = p.id')
            ->leftJoin('disorder do',' do.id = sd.disorder_id')
            ->group('p.id, p.hos_num, c.first_name, c.last_name, p.dob, t_p.external_trial_identifier, t_p.treatment_type, t_p.patient_status')
            ->order('c.first_name, c.last_name');
    }

    /**
     * Runs the report and adds the result set to $patients
     */
    public function run()
    {
        $select = 'p.id, p.hos_num, c.first_name, c.last_name, p.dob, t_p.external_trial_identifier, t_p.treatment_type, t_p.patient_status, GROUP_CONCAT(DISTINCT d.name) as name, GROUP_CONCAT(DISTINCT do.term) as term';

        $query = $this->getDbCommand();

        $or_conditions = array('t.id=:id');
        $whereParams = array(':id' => $this->trialID);

        $query->select($select);
        $condition = '( ' . implode(' AND ', $or_conditions) . ' )';

        $query->where($condition, $whereParams);

        foreach ($query->queryAll() as $item) {
            $this->addPatientResultItem($item);
        }
    }

    /**
     * Gets the description message to display at the top of the report
     *
     * @return string
     */
    public function description()
    {
        /* @var Trial $trial */
        $trial = Trial::model()->findByPk($this->trialID);

        return "Patients shortlisted for $trial->name";
    }

    /**
     * Adds one result row to $patients
     *
     * @param array $item
     */
    public function addPatientResultItem($item)
    {
        $this->patients[$item['id']] = array(
            'hos_num' => $item['hos_num'],
            'dob' => $item['dob'],
            'first_name' => $item['first_name'],
            'last_name' => $item['last_name'],
            'external_trial_identifier' => $item['external_trial_identifier'],
            'treatment_type' => $item['treatment_type'],
            'patient_status' => $item['patient_status'],
            'name' => $item['name'],
            'term' => $item['term'],
        );
    }

    /**
     * Output the report in CSV format.
     *
     * @return string
     */
    public function toCSV()
    {
        $output = $this->description() . "\n\n";

        $output .= Patient::model()->getAttributeLabel('hos_num') . ',' . Patient::model()->getAttributeLabel('dob') . ',' . Patient::model()->getAttributeLabel('first_name') . ',' . Patient::model()->getAttributeLabel('last_name') . ',' . TrialPatient::model()->getAttributeLabel('external_trial_identifier') . ',' . TrialPatient::model()->getAttributeLabel('treatment_type') . ',' . TrialPatient::model()->getAttributeLabel('patient_status') . ',' . Drug::model()->getAttributeLabel('name') .',' . Disorder::model()->getAttributeLabel('term') ."\n";

        foreach ($this->patients as $ts => $patient) {
            $output .= "\"{$patient['hos_num']}\",\"" . ($patient['dob'] ? date('j M Y',
                    strtotime($patient['dob'])) : 'Unknown') . "\",\"{$patient['first_name']}\",\"{$patient['last_name']}\",\"{$patient['external_trial_identifier']}\",\"{$patient['treatment_type']}\", {$patient['patient_status']},\"{$patient['name']}\",\"{$patient['term']}\"" . "\n";
        }

        return $output;
    }
}