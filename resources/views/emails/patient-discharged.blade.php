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
									<td class="h1 pb25" style="color:#ffffff; font-family:'Muli', Arial,sans-serif; font-size:40px; line-height:46px; text-align:left; padding-bottom:25px;"><div mc:edit="text_2">Hello Admin,</div></td>
								</tr>
								<tr>
									<td class="text-center pb25" style="color:#fff; font-family:'Muli', Arial,sans-serif; font-size:16px; line-height:22px; text-align:left; padding-bottom:25px;">
										<div mc:edit="text_3"> 
											Patient discharge request has been raised by {{$role}} with following detail:<br><br>
											<b>Care Home:</b> {{ $patient->care_home ? $patient->care_home->name : '' }} <br>
											<b>Patient Name:</b> {{ $patient->name }} <br>
											<b>Email:</b> {{ $patient->email }} <br>
											<b>Phone:</b> {{ $patient->phone }} <br>
											<b>Address:</b> {{ $patient->address }}
										</div>
									</td>
								</tr>
								<tr mc:hideable>
									<td align="">
										<table class="center" border="0" cellspacing="0" cellpadding="0" style="text-align:left;">
											<tr>
												<td class="pink-button text-button" style="background:#254A45; color:#c1cddc; font-family:'Muli', Arial,sans-serif; font-size:14px; line-height:18px; padding:12px 30px; text-align:center; border-radius:0px 22px 22px 22px; font-weight:bold;"><div mc:edit="text_4"><a href="{{ $actionUrl }}" target="_blank" class="link-white" style="color:#ffffff; text-decoration:none;"><span class="link-white" style="color:#ffffff; text-decoration:none;">{{$actionText}}</span></a></div></td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td class="text-center pb25" style="color:#fff; font-family:'Muli', Arial,sans-serif; font-size:16px; line-height:22px; text-align:left; padding-bottom:25px;"><div mc:edit="text_3">Best regards, <br>
               
									{{ config('app.name')}}</div></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
@endsection