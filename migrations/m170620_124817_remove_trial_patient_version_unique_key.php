<?php

class m170620_124817_remove_trial_patient_version_unique_key extends OEMigration
{
    public function safeUp()
    {
        $this->execute("ALTER TABLE trial_patient_version DROP INDEX trial_patient_unique");
    }

    public function safeDown()
    {
        $this->execute("ALTER TABLE trial_patient_version ADD UNIQUE INDEX trial_patient_unique (trial_id, patient_id)");
    }
}