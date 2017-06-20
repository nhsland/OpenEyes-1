<?php

class m170619_011706_add_trial_closed_date_column extends OEMigration
{
    public function safeUp()
    {
        $this->addColumn('trial', 'closed_date', 'datetime');
        $this->addColumn('trial_version', 'closed_date', 'datetime');
    }

    public function safeDown()
    {
        $this->dropColumn('trial', 'closed_date');
        $this->dropColumn('trial_version', 'closed_date');
    }
}