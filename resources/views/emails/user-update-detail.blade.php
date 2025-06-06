@extends('layouts.email')

@section('content')
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td style="padding-bottom: 10px;">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td class="tbrr p30-15" style="padding: 60px 30px; border-radius:0px 0px 0px 0px;" bgcolor="#08A29E">
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td class="h1 pb25" style="color:#ffffff; font-family:'Muli', Arial,sans-serif; font-size:40px; line-height:46px; text-align:left; padding-bottom:25px;">
										<div mc:edit="text_2">Profile Update Notification</div>
									</td>
								</tr>
								<tr>
									<td class="text-center pb25" style="color:#fff; font-family:'Muli', Arial,sans-serif; font-size:16px; line-height:22px; text-align:left; padding-bottom:25px;">
										<div mc:edit="text_3"> 
											Dear {{ $data['name'] }},<br><br>
											Your profile has been updated by an administrator. Here are the new details:<br>
											<b>Name:</b> {{ $data['name'] }} <br>
											<b>Email:</b> {{ $data['email'] }} <br>
											<b>Phone Number:</b> {{ $data['phone_number'] }}<br>
											<b>Status:</b> {{ ($data['status']==1)? 'Active' :'Inactive' }}
										</div>
									</td>
								</tr>
								
								<tr>
									<td class="text-center pb25" style="color:#fff; font-family:'Muli', Arial,sans-serif; font-size:16px; line-height:22px; text-align:left; padding-bottom:25px;">
										<div mc:edit="text_3">Thank you .</div>
									</td>
								</tr>
								<!-- Button -->
							
								<!-- END Button -->
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
@endsection
