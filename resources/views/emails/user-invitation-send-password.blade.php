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
									<td class="h1 pb25" style="color:#ffffff; font-family:'Muli', Arial,sans-serif; font-size:40px; line-height:46px; text-align:left; padding-bottom:25px;"><div mc:edit="text_2">Welcome, {{ $data['name'] }}</div></td>
								</tr>
								<tr>
									<td class="text-center pb25" style="color:#fff; font-family:'Muli', Arial,sans-serif; font-size:16px; line-height:22px; text-align:left; padding-bottom:25px;">
										<div mc:edit="text_3"> 
											Your account login details:<br>
											<b>Email:</b> {{ $data['email'] }} <br>
											<b>Password:</b> {{ $data['password'] }}
										</div>
									</td>
								</tr>
								<tr>
									<td class="text-center pb25" style="color:#fff; font-family:'Muli', Arial,sans-serif; font-size:16px; line-height:22px; text-align:left; padding-bottom:25px;"><div mc:edit="text_3"><p>Please download and install mobile app to login.</p>
										<a href="https://play.google.com/store/apps/details?id=com.rnhealthcare&pcampaignid=web_share"><img src="{{asset('assets/front-images/google-play.png')}}"></a>
									</div></td>
								</tr>
								
								<tr>
									{{-- <td class="text-center pb25" style="color:#fff; font-family:'Muli', Arial,sans-serif; font-size:16px; line-height:22px; text-align:left; padding-bottom:25px;"><div mc:edit="text_3">We may need to send you critical information about our service and it is important that we have an accurate email address.</div></td> --}}
									<td class="text-center pb25" style="color:#fff; font-family:'Muli', Arial,sans-serif; font-size:16px; line-height:22px; text-align:left; padding-bottom:25px;">
									<div mc:edit="text_3">
									Step 1: Download the Docryt App.
									Step 2: Sign in with the details [email and temporal password] above.
									Step 3: Click on the profile picture on the top right corner in the app to change password.
									</div></td>
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