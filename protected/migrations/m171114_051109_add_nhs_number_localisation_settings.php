<?php

class m171114_051109_add_nhs_number_localisation_settings extends OEMigration
{
    public function safeUp()
    {
        $this->insert('setting_metadata', array(
            'element_type_id' => null,
            'field_type_id' => 4, // Text field
            'key' => 'nhs_number_label',
            'name' => 'NHS Number Label',
            'default_value' => 'NHS Number',
        ));

        $this->insert('setting_metadata', array(
            'element_type_id' => null,
            'field_type_id' => 4, // Text field
            'key' => 'nhs_no_label',
            'name' => 'NHS No Label',
            'default_value' => 'NHS No',
        ));
    }

    public function safeDown()
    {
        $this->delete('setting_metadata', '`key` = \'nhs_number_label\'');
        $this->delete('setting_metadata', '`key` = \'nhs_no_label\'');
    }
}