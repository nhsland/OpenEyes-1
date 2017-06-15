<?php

/**
 * Created by PhpStorm.
 * User: andre
 * Date: 15/06/2017
 * Time: 10:47 AM
 */
class OETrial_ReportCohort extends BaseReport
{
    public $trialID;
    public $patients = array();

    public function rules()
    {
        return array(
            array('trialID', 'safe'),
        );
    }

    public function getDbCommand()
    {
        return Yii::app()->db->createCommand()
            ->from('trial t')
            ->join('trial_patient t_p', 't.id = t_p.trial_id')
            ->join('patient p', 'p.id = t_p.patient_id')
            ->join('contact c', 'p.contact_id = c.id');
    }

    public function run()
    {
        $select = 'p.id, p.hos_num, c.first_name, c.last_name, p.dob';

        $query = $this->getDbCommand();

        $or_conditions = array('t.id=:id');
        $whereParams = array(':id' => $this->trialID);

        $query->select($select);
        $condition = '( ' . implode(' and ', $or_conditions) . ' )';

        $query->where($condition, $whereParams);

        foreach ($query->queryAll() as $item) {
            $this->addPatientResultItem($item);
        }
    }

    public function description()
    {
        $trial = Trial::model()->findByPk($this->trialID);

        return "Patients shortlisted for $trial->name";
    }

    public function addPatientResultItem($item)
    {
        $ts = $item['id'];

        $this->patients[$ts] = array(
            'hos_num' => $item['hos_num'],
            'dob' => $item['dob'],
            'first_name' => $item['first_name'],
            'last_name' => $item['last_name'],
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

        $output .= Patient::model()->getAttributeLabel('hos_num') . ',' . Patient::model()->getAttributeLabel('dob') . ',' . Patient::model()->getAttributeLabel('first_name') . ',' . Patient::model()->getAttributeLabel('last_name') . "\n";

        foreach ($this->patients as $ts => $patient) {
            $output .= "\"{$patient['hos_num']}\",\"" . ($patient['dob'] ? date('j M Y',
                    strtotime($patient['dob'])) : 'Unknown') . "\",\"{$patient['first_name']}\",\"{$patient['last_name']}\",\"" . date('j M Y',
                    $ts) . "\"\n";
        }

        return $output;
    }
}