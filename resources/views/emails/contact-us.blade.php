@extends('layouts.email')
@section('content')
<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" class="templateContainer"
    style="background-color: #ffffff;">
    <tbody>
        <tr>
            <td valign="top" class="bodyContainer"
                style="font-size: 16px; text-align:center; color:rgba(102, 102, 102, 1);line-height: 30px;">
                <h2>Hi Admin,</h2>
            </td>
        </tr>
        <tr>
            <td valign="top" class="mcnTextContent"
                style="padding-top:0; padding-right:18px; padding-bottom:9px; padding-left:18px;font-size: 14px;line-height: 21px;">
			
					<p>Name: {{ $data['name'] }}</p>
					<p>Email: {{ $data['email'] }}</p>
					<p>Subject: {{ $data['subject'] }}</p>
					<p>Message: {{ $data['message'] }}</p>

                
            </td>
        </tr>
        
    </tbody>
</table>
<!-- // END TEMPLATE -->
@endsection