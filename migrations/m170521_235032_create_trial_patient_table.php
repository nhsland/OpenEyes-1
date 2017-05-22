<?php

class m170521_235032_create_trial_patient_table extends CDbMigration
{
    public function up()
    {
        $this->createTable('trial_patient', array(
            'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
            'external_trial_identifier' => 'varchar(64) collate utf8_bin',
            'trial_id' => 'int(10) unsigned NOT NULL',
            'patient_id' => 'int(10) unsigned NOT NULL',
            'patient_status' => 'int(10) unsigned NOT NULL',
            'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
            'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
            'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
            'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
            'PRIMARY KEY (`id`)',
            'KEY `trial_patient_trial_id_fk` (`trial_id`)',
            'KEY `trial_patient_patient_id_fk` (`patient_id`)',
            'KEY `trial_patient_created_user_id_fk` (`created_user_id`)',
            'KEY `trial_patient_last_modified_user_id_fk` (`last_modified_user_id`)',
            'CONSTRAINT `trial_patient_trial_id_fk` FOREIGN KEY (`trial_id`) REFERENCES `trial` (`id`)',
            'CONSTRAINT `trial_patient_patient_id_fk` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`id`)',
            'CONSTRAINT `trial_patient_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
            'CONSTRAINT `trial_patient_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
        ), 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
        );
    }

    public function down()
    {
        $this->dropTable('trial_patient');
    }
}
