<?php

class m170703_031755_add_treatment_type_to_trial_patient extends CDbMigration
{
    public function safeUp()
    {
        $this->addColumn('trial_patient', 'treatment_type', 'int(10) unsigned not null default 0');
        $this->addColumn('trial_patient_version', 'treatment_type', 'int(10) unsigned not null default 0');
    }

    public function safeDown()
    {
        $this->dropColumn('trial_patient', 'treatment_type');
        $this->dropColumn('trial_patient_version', 'treatment_type');
    }
}