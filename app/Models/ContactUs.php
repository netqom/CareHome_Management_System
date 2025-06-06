<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;

class ContactUs extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'email','subject','message', 'status', 'created_by', 'updated_by'];
	
	public function addUpdateContactUs($request, $id)
	{
		$contact_data = [
			'name'       => $request->name,
            'email'    => $request->email,
            'subject'    => $request->subject,
            'message'    => $request->message,
            'status'     => 1,
			'updated_by' => 0,
        ];
        if ($id != 0) {
            $contact = $this->findOrFail($id);
            $contact = $contact->update($contact_data);
        } else {
            $page_data['created_by'] = 0;
            $contact = $this->create($contact_data);
        }
        return true;
	}
}
