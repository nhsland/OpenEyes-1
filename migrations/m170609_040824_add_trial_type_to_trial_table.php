<?php

class m170609_040824_add_trial_type_to_trial_table extends OEMigration
{
    public function safeUp()
    {
        $this->addColumn('trial', 'trial_type', 'integer unsigned not null default 0');
        $this->addColumn('trial_version', 'trial_type', 'integer unsigned not null default 0');
    }

    public function safeDown()
    {
        $this->dropColumn('trial', 'trial_type');
        $this->dropColumn('trial_version', 'trial_type');
    }
}