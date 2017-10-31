<?php

/**
 * Class CaseSearchParameter
 * @property $name string
 * @property $operation mixed
 * @property $id mixed
 * @property $joinCondition string
 * @property $level string
 */
abstract class CaseSearchParameter extends CFormModel
{
    public $name;
    public $operation;
    public $id;
    public $joinCondition = 'AND';
    public $level= 0; // Default group is top-level group.

    /**
     * Get the parameter identifier (usually the name).
     * @return string The human-readable name of the parameter (for display purposes).
     */
    abstract public function getLabel();

    /**
     * Override this function for any new attributes added to the subclass. Ensure that you invoke the parent function first to obtain and augment the initial list of attribute names.
     * @return string[] An array of attribute names.
     */
    public function attributeNames()
    {
        return array('name', 'operation', 'id');
    }

    /**
     * Override this function if the parameter subclass has extra validation rules. If doing so, ensure you invoke the parent function first to obtain the initial list of rules.
     * @return array The validation rules for the parameter.
     */
    public function rules()
    {
        return array(
            array('operation', 'required'),
            array('operation, id, joinCondition, level', 'safe')
        );
    }

    /**
     * Render the parameter on-screen.
     * @param $id integer The position of the parameter in the list of parameters.
     */
    abstract public function renderParameter($id);

    /**
     * Override this function to customise the output within the audit table. Generally it should be something like "name: < val".
     * @return string|null The audit string.
     */
    public function getAuditData()
    {
        return null;
    }
}