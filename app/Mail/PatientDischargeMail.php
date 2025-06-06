<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Auth;

class PatientDischargeMail extends Mailable 
{
   use Queueable;
        public $patient;
    
        /**
         * Create a new notification instance.
         *
         * @return void
         */
        public function __construct($patient)
        {
            $this->patient =  $patient;
        }
		
		/**
		 * Build the message.
		 *
		 * @return $this
		 */
		public function build()
		{
			$role = Auth::user()->role_id == '4' ? 'Staff' : 'Manager';
			$actionText = 'View Patient';
			$actionUrl = route('patients.show', $this->patient->id);
			return $this->subject('Patient discharge requested raised by '.$role)
							->view('emails.patient-discharged', [
								'patient' => $this->patient,
								'actionText' => $actionText,
								'actionUrl' => $actionUrl,
								'role'	=> $role,
							 ]);
		}
		
}