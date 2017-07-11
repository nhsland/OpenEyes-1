<?php

/**
 * @inherit
 */
class PreviousTrialParameter extends CaseSearchParameter implements DBProviderInterface
{
    public $trial;
    public $type;

    /**
     * CaseSearchParameter constructor. This overrides the parent constructor so that the name can be immediately set.
     * @param string $scenario
     */
    public function __construct($scenario = '')
    {
        parent::__construct($scenario);
        $this->name = 'previous_trial';
    }

    public function getLabel()
    {
        // This is a human-readable value, so feel free to change this as required.
        return 'Previous Trial';
    }

    /**
     * Override this function for any new attributes added to the subclass. Ensure that you invoke the parent function first to obtain and augment the initial list of attribute names.
     * @return array An array of attribute names.
     */
    public function attributeNames()
    {
        return array_merge(parent::attributeNames(), array(
                'trial',
                'type'
            )
        );
    }

    /**
     * Override this function if the parameter subclass has extra validation rules. If doing so, ensure you invoke the parent function first to obtain the initial list of rules.
     * @return array The validation rules for the parameter.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
                array('type, trial', 'safe'),
            )
        );
    }

    public function renderParameter($id)
    {
        $ops = array(
            '=' => 'Has been in',
            '!=' => 'Has never been in',
        );

        $types = Trial::getTrialTypeOptions();

        ?>

      <div class="large-2 column">
          <?php echo CHtml::label($this->getLabel(), false); ?>
      </div>
      <div class="large-3 column">
          <?php echo CHtml::activeDropDownList($this, "[$id]operation", $ops, array('prompt' => 'Select One...')); ?>
          <?php echo CHtml::error($this, "[$id]operation"); ?>
      </div>

      <div class="large-2 column trial-type">
          <?php echo CHtml::activeDropDownList($this, "[$id]type", $types,
              array('empty' => 'Any Trial', 'onchange' => 'getTrialList(this)')); ?>
      </div>

      <div class="large-3 column trial-list">
        <p></p>
      </div>

      <script type="text/javascript">
        function getTrialList(target) {
          var type = parseInt($(target).val());
          var id = $(target).parent().parent().parent().attr('id');

          if (type === null) {
            $(target).parent().parent().find('.trial-list').replaceWith('<div class="large-3 column trial-list"><p></p></div>');
          }
          else {
            $.ajax({
              url: '<?php echo Yii::app()->createUrl('/OETrial/trial/getTrialList'); ?>',
              type: 'GET',
              data: {trialID: id, type: type},
              success: function(response) {
                $(target).parent().parent().find('.trial-list').replaceWith(response);
              }
            });
          }
        }
      </script>

        <?php
    }

    /**
     * Generate a SQL fragment representing the subquery of a FROM condition.
     * @param $searchProvider SearchProvider The search provider. This is used to determine whether or not the search provider is using SQL syntax.
     * @return mixed The constructed query string.
     * @throws CHttpException
     */
    public function query($searchProvider)
    {
        // Construct your SQL query here.
        if ($searchProvider->providerID === 'mysql') {
            switch ($this->operation) {
                case '=':
                    $joinCondition = 'JOIN';
                    if ($this->type !== '' && $this->type !== null) {
                        if ($this->trial === '') {
                            // Any intervention/non-intervention trial
                            $condition = "t.trial_type = :p_t_type_$this->id";
                        } else {
                            // specific trial
                            $condition = "t_p.trial_id = :p_t_trial_$this->id";
                        }

                    } else {
                        // Any trial
                        $condition = 't_p.trial_id IS NOT NULL';
                    }
                    break;
                case '!=':
                    $joinCondition = 'LEFT JOIN';
                    if ($this->type !== '' && $this->type !== null) {
                        $condition = 't_p.trial_id IS NULL OR ';
                        if ($this->trial === '') {
                            // Not in any intervention/non-intervention trial
                            $condition .= "t.trial_type != :p_t_type_$this->id";
                        } else {
                            // Not in a specific trial
                            $condition .= "t_p.trial_id != :p_t_trial_$this->id";
                        }
                    } else {
                        // not in any trial
                        $condition = 't_p.trial_id IS NULL';
                    }
                    break;
                default:
                    throw new CHttpException(400, 'Invalid operator specified.');
                    break;
            }

            return "
SELECT p.id 
FROM patient p 
$joinCondition trial_patient t_p 
  ON t_p.patient_id = p.id 
$joinCondition trial t
  ON t.id = t_p.trial_id
WHERE $condition";
        } else {
            return null; // Not yet implemented.
        }
    }

    /**
     * Get the list of bind values for use in the SQL query.
     * @return array An array of bind values. The keys correspond to the named binds in the query string.
     */
    public function bindValues()
    {
        // Construct your list of bind values here. Use the format "bind" => "value".
        $binds = array();

        if ($this->trial !== '' and $this->trial !== null) {
            $binds[":p_t_trial_$this->id"] = $this->trial;
        } elseif ($this->type !== '' and $this->type !== null) {
            $binds[":p_t_type_$this->id"] = $this->type;
        }

        return $binds;
    }
}
