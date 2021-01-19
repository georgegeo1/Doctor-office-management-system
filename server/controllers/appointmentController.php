<?php

include_once 'BaseController.php';
include_once 'userController.php';
include_once 'optionsController.php';
include_once 'doctor_officeController.php';
include_once 'doctor_worktimeController.php';

include_once __DIR__ . '/../common/MAIL.php';
include_once __DIR__ . '/../common/UTILS.php';

session_start();

class appointmentController extends BaseController {

    /**
     * Returns the meta data for the Browser
     * 
     * @return meta data as json object
     */
    protected static function getBrowserMeta() {
        return json_decode(
                '['
                . '        {'
                . '         "name":"master",'
                . '         "keyField":"appointment_id",'
                . '         "model":"appointments"'
                . '        }'
                . ']'
        );
    }

    /**
     * Returns the meta data for the Loader
     * 
     * @return meta data as json object
     */
    protected static function getLoaderMeta() {
        return json_decode(
                '['
                . '        {'
                . '         "name":"master",'
                . '         "keyField":"appointment_id",'
                . '         "model":"appointment"'
                . '        }'
                . ']'
        );
    }

    /**
     * Checks if a doctor is working on a given date/time. Otherwise, it throws 
     * an exception
     * 
     * @param $app_doctor_id The Doctor's id
     * @param $app_date      The specified date
     * @param $app_time      The specified time
     */
    private static function checkDateTimeUponDoctorWorkTime($app_doctor_id, $app_date, $app_time) {
        $doctorWorkTime = doctor_worktimeController::browse(
                        UTILS::ToJsonObj(
                                array(
                                    'where' => array(
                                        array(
                                            'field' => 'doctor_worktime_time',
                                            'op' => '=',
                                            'value' => $app_time
                                        ),
                                        array(
                                            'field' => 'doctor_worktime_date',
                                            'op' => '=',
                                            'value' => $app_date
                                        ),
                                        array(
                                            'field' => 'doctor_worktime_doctor_id',
                                            'op' => '=',
                                            'value' => $app_doctor_id
                                        )
                                    )
                                )
                        )
        );


        if (count($doctorWorkTime->data[0]->records) === 0) {
            throw new Exception("The doctor is not working at this date and time!");
        }
    }

    /**
     * Checks if an appointment already exists for a specific doctor, date and time.
     * Otherwise, it throws an exception.
     * 
     * @param $app_doctor_id  The Doctor's id
     * @param $app_date       The specified date
     * @param $app_time       The specified time
     */
    private static function checkAppUniqueTime($app_doctor_id, $app_date, $app_time) {
        $appPacket = static::browse(
                        UTILS::ToJsonObj(
                                array(
                                    'where' => array(
                                        array(
                                            'field' => 'appointment_time',
                                            'op' => '=',
                                            'value' => $app_time
                                        ),
                                        array(
                                            'field' => 'appointment_date',
                                            'op' => '=',
                                            'value' => $app_date
                                        ),
                                        array(
                                            'field' => 'appointment_doctor_id',
                                            'op' => '=',
                                            'value' => $app_doctor_id
                                        )
                                    )
                                )
                        )
        );

        if (count($appPacket->data[0]->records) > 0) {
            throw new Exception("Not available date/time!");
        }
    }

    /**
     * Checks if an appointment can be deleted.
     * Otherwise, it throws an exception.
     * 
     * @param $app_user_id  The id of the user that has inserted the appointment
     * @param $app_state    The current appointment's state 
     */
    private static function checkAppDelete($app_user_id, $app_state) {
        if ($app_state !== '0') { // if state is not "PENDING"
            throw new Exception("A not PENDING appointment can not be deleted!");
        }

        $userPacket = userController::load($app_user_id);

        if ($userPacket && count($userPacket->data[0]->records)) {
            $user = $userPacket->data[0]->records[0];

            if ($user->user_type === '0') {
                throw new Exception("An appointment inserted by patient can not be deleted!");
            }
        }
    }

    /**
     * Sends an e-mail messsage to a user.
     * 
     * @param $message  The e-mail's message text
     * @param $userId   The id of the recipient. If it is null then the recipient 
     *                  is the doctor office itself.  
     */
    private static function sendMail($message, $userId = null) {
        $email = null;

        if ($userId) {
            $userPacket = userController::load($userId);

            if ($userPacket && count($userPacket->data[0]->records)) {
                $user = $userPacket->data[0]->records[0];

                $email = $user->user_email;
            }
        } else {
            $doctor_officePacket = doctor_officeController::load(1);

            if ($doctor_officePacket && count($doctor_officePacket->data[0]->records)) {
                $doctor_office = $doctor_officePacket->data[0]->records[0];

                $email = $doctor_office->doctor_office_contact_email;
            }
        }

        if ($email) {
            $options = optionsController::getOptionsRecord();

            if ($options) {
                $mail_host = $options->options_mail_host;
                $mail_username = $options->options_mail_username;
                $mail_password = $options->options_mail_password;

                MAIL::SendMail($mail_host, $mail_username, $mail_password, $email, "CNCL", $message);
            }
        }
    }

    /**
     * Executed before a (new, updated or deleted) record is saved to db.
     * 
     * @param $modelName      The name of the current model
     * @param $modelClassName The definition class of the current model
     * @param $modelFileName  The php file of the definition class of the current model
     * @param $keyField       The key field of the model
     * @param $dataDBMaster   The old version of the record to be saved
     * @param $dataNEWMaster  The new version of the record to be saved
     */ 
    protected static function onBeforeSave($modelName, $modelClassName, $modelFileName, $keyField, $dataDBMaster, $dataNEWMaster) {
        // Set "appointment_user_id" field

        if ($dataNEWMaster->_state == '2') {
            $dataNEWMaster->appointment_user_id = $_SESSION['userId'];
        }

        // (FR28) The system allows to delete only pending Appointments that has not been inserted by patients.

        if ($dataNEWMaster->_state == '3') {
            static::checkAppDelete($dataNEWMaster->appointment_user_id, $dataNEWMaster->appointment_state);
        }

        // (FR23, FR37) Check appointment date/time upon doctor's working hours

        if ($dataNEWMaster->_state == '1' || $dataNEWMaster->_state == '2') {
            static::checkDateTimeUponDoctorWorkTime($dataNEWMaster->appointment_doctor_id, $dataNEWMaster->appointment_date, $dataNEWMaster->appointment_time);
        }

        // (FR37) Check appointment date/time uniqueness

        if ((($dataNEWMaster->_state == '1') &&
                ($dataDBMaster->appointment_doctor_id != $dataNEWMaster->appointment_doctor_id ||
                $dataDBMaster->appointment_date != $dataNEWMaster->appointment_date ||
                $dataDBMaster->appointment_time != $dataNEWMaster->appointment_time)) ||
                ($dataNEWMaster->_state == '2')) {
            static::checkAppUniqueTime($dataNEWMaster->appointment_doctor_id, $dataNEWMaster->appointment_date, $dataNEWMaster->appointment_time);
        }

        // (FR29) Modifying the state of a pending (and only pending) Appointment to "ACCEPTED" or "REJECTED"

        if ($dataNEWMaster->_state == '1' || $dataNEWMaster->_state == '2') {
            if (($dataNEWMaster->appointment_state == '1' || $dataNEWMaster->appointment_state == '2') &&
                    ($dataDBMaster->appointment_state != $dataNEWMaster->appointment_state)) {
                if ($dataDBMaster->appointment_state != '0') {
                    throw new Exception('An appointment can be "ACCEPTED" or "REJECTED" only if it is - currently - "PENDING"!');
                }
            }
        }

        // (FR31) Closing a previously accepted Appointment as if it has been completed, after opening it first

        if ($dataNEWMaster->_state == '1' || $dataNEWMaster->_state == '2') {
            if (($dataNEWMaster->appointment_state == '3') &&
                    ($dataDBMaster->appointment_state != $dataNEWMaster->appointment_state)) {
                if ($dataDBMaster->appointment_state != '1') {
                    throw new Exception('An appointment can be "COMPLETED" only if it is - currently - "ACCEPTED"!');
                }
            }
        }
    }

    /**
     * Executed after a (new, updated or deleted) record has been saved to db.
     * 
     * @param $modelName      The name of the current model
     * @param $modelClassName The definition class of the current model
     * @param $modelFileName  The php file of the definition class of the current model
     * @param $keyField       The key field of the model
     * @param $dataDBMaster   The old version of the record to be saved
     * @param $dataNEWMaster  The new version of the record to be saved
     */ 
    protected static function onAfterSave($modelName, $modelClassName, $modelFileName, $keyField, $dataDBMaster, $dataNEWMaster) {
        parent::onAfterSave($modelName, $modelClassName, $modelFileName, $keyField, $dataDBMaster, $dataNEWMaster);

        // (FR29, FR31) The E-Mail Subsystem sends an automated e-mail message to the patient's email address, to inform him (or her).

        if ($dataDBMaster->appointment_state != $dataNEWMaster->appointment_state) {
            if ($dataNEWMaster->appointment_state == '1') {
                static::sendMail('The appointment has been accepted', $dataNEWMaster->appointment_patient_id);
            } else if ($dataNEWMaster->appointment_state == '2') {
                static::sendMail('The appointment has been rejected', $dataNEWMaster->appointment_patient_id);
            } else if ($dataNEWMaster->appointment_state == '3') {
                static::sendMail('The appointment has been closed', $dataNEWMaster->appointment_patient_id);
            }
        }

        // (FR30) The E-Mail Subsystem sends an automated e-mail message to the patient's email address, to inform him (or her).

        if (($dataDBMaster->appointment_cncl !== $dataNEWMaster->appointment_cncl) &&
                ($dataNEWMaster->appointment_cncl === '1')) {
            if ($_SESSION['userId'] != $dataNEWMaster->appointment_patient_id) {
                static::sendMail('The appointment has been cancelled', $dataNEWMaster->appointment_patient_id);
            } else {
                static::sendMail('The appointment has been cancelled');
            }
        }
    }

}
