<?php

class m170620_080037_increase_trial_description_limit extends OEMigration
{
    // Use safeUp/safeDown to do migration with transaction
    public function safeUp()
    {
        $this->alterColumn('trial', 'description', 'text');
        $this->alterColumn('trial_version', 'description', 'text');
    }

    public function safeDown()
    {
        $this->alterColumn('trial', 'description', 'varchar(64)');
        $this->alterColumn('trial_version', 'description', 'varchar(64)');
    }
}