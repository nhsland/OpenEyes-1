<?php
/* @var $this TrialPatientController */
/* @var $data TrialPatient */
?>

<div class="result box generic">

    <b><?php echo CHtml::encode($data->getAttributeLabel('external_trial_identifier')); ?>:</b>
    <span id="ext-trial-id-<?php echo $data->id; ?>"><?php echo CHtml::encode($data->external_trial_identifier); ?></span>
    <a id="ext-trial-id-link-<?php echo $data->id; ?>" href="javascript:void(0)"
       onclick="editExternalTrialIdentifier(<?php echo $data->id; ?>)">edit</a>

    <form id="ext-trial-id-form-<?php echo $data->id; ?>" style="display:none">
        <input id="trial-patient-ext-id-<?php echo $data->id; ?>" type="text"
               value="<?php echo $data->external_trial_identifier; ?>" width="50"/>
        <a id="ext-trial-id-save-<?php echo $data->id; ?>" href="javascript:void(0)"
           onclick="saveExternalTrialIdentifier(<?php echo $data->id; ?>)">save</a>
    </form>
    <br/>

    <h3 class="box-title">
        <?php echo CHtml::link(
            $data->patient->contact->last_name . ', ' . $data->patient->contact->first_name . ($data->patient->is_deceased ? ' (Deceased)' : ''),
            array('/patient/view', 'id' => $data->patient->id),
            array('target' => '_blank')
        ); ?>
    </h3>
    <div class="row data-row">
        <div class="large-12 column">
            <?php
            echo $data->patient->getGenderString() . ' ' . '(' . $data->patient->getAge() . ') ';
            if ($data->patient->ethnic_group) {
                echo $data->patient->getEthnicGroupString();
            }
            ?>
        </div>
    </div>

    <?php if ($data->patient->secondarydiagnoses): ?>
        <div class="row data-row">
            <div class="large-12 column">
                <div>Medications:
                    <a href="javascript:void(0)" class="section-toggle"
                       data-show-label="show" data-hide-label="hide"
                       onclick="toggleSection(this, '#collapse-section_<?php echo $data->id . '_diagnosis'; ?>');">show
                    </a>
                </div>
                <div id="collapse-section_<?php echo $data->id . '_diagnosis'; ?>" style="display:none">
                    <div class="diagnoses detail row data-row">
                        <div class="large-12 column">
                            <table>
                                <thead>
                                <tr>
                                    <th>Diagnosis</th>
                                    <th>Date</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($data->patient->secondarydiagnoses as $diagnosis): ?>
                                    <tr>
                                        <td><?php echo $diagnosis->disorder->fully_specified_name; ?></td>
                                        <td><?php echo $diagnosis->dateText; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>


    <?php if ($data->patient->medications): ?>
        <div class="row data-row">
            <div class="large-12 column">
                <div>Medications:
                    <a href="javascript:void(0)" class="section-toggle"
                       data-show-label="show" data-hide-label="hide"
                       onclick="toggleSection(this, '#collapse-section_<?php echo $data->id . '_medication'; ?>');">show
                    </a>
                </div>

                <div id="collapse-section_<?php echo $data->id . '_medication'; ?>" style="display:none">
                    <div class="medications detail row data-row">
                        <div class="large-12 column">
                            <table>
                                <thead>
                                <tr>
                                    <th>Medication</th>
                                    <th>Administration</th>
                                    <th>Date From</th>
                                    <th>Date To</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($data->patient->medications as $medication): ?>
                                    <tr>
                                        <td><?php echo $medication->getDrugLabel(); ?></td>
                                        <td><?= $medication->dose ?>
                                            <?= isset($medication->route->name) ? $medication->route->name : '' ?>
                                            <?= $medication->option ? "({$medication->option->name})" : '' ?>
                                            <?= isset($medication->frequency->name) ? $medication->frequency->name : '' ?></td>
                                        <td><?php echo Helper::formatFuzzyDate($medication->start_date); ?></td>
                                        <td><?php echo isset($medication->end_date) ? Helper::formatFuzzyDate($medication->end_date) : ''; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($data->patient_status == TrialPatient::STATUS_SHORTLISTED): ?>
        <button onclick="changePatientStatus(this, <?php echo $data->id; ?>, <?php echo TrialPatient::STATUS_ACCEPTED; ?>)"
                class="accept-patient-link">Accept Patient
        </button>
    <?php endif; ?>

    <?php if ($data->patient_status == TrialPatient::STATUS_SHORTLISTED || $data->patient_status == TrialPatient::STATUS_ACCEPTED): ?>
        <button onclick="changePatientStatus(this, <?php echo $data->id; ?>, <?php echo TrialPatient::STATUS_REJECTED; ?>)"
                class="accept-patient-link">Reject Patient
        </button>
    <?php endif; ?>

    <?php if ($data->patient_status == TrialPatient::STATUS_REJECTED): ?>
        <button onclick="changePatientStatus(this, <?php echo $data->id; ?>, <?php echo TrialPatient::STATUS_SHORTLISTED; ?>)"
                class="accept-patient-link">Shortlist Patient
        </button>
    <?php endif; ?>
</div>

