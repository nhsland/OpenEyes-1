<?php

class m170711_024649_add_external_ref_column extends OEMigration
{
    // Use safeUp/safeDown to do migration with transaction
    public function safeUp()
    {
        $this->addColumn('trial', 'external_reference', 'varchar(100)');
        $this->addColumn('trial_version', 'external_reference', 'varchar(100)');
    }

    public function safeDown()
    {
        $this->dropColumn('trial', 'external_reference');
        $this->dropColumn('trial_version', 'external_reference');
    }
}