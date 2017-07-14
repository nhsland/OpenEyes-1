<?php

class m170712_001059_add_role_to_user_trial_permission_table extends OEMigration
{
    public function safeUp()
    {
        $this->addColumn('user_trial_permission', 'role', 'varchar(255)');
        $this->addColumn('user_trial_permission_version', 'role', 'varchar(255)');
    }

    public function safeDown()
    {
        $this->dropColumn('user_trial_permission', 'role');
        $this->dropColumn('user_trial_permission_version', 'role');
    }
}