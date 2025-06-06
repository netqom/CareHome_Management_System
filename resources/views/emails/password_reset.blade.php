@extends('layouts.email')

@section('content')
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td style="padding-bottom: 10px;">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td class="tbrr p30-15" style="padding: 60px 30px; border-radius:0px 0px 0px 0px;" bgcolor="#0097b2">
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td class="h1 pb25" style="color:#ffffff; font-family:'Muli', Arial,sans-serif; font-size:40px; line-height:46px; text-align:left; padding-bottom:25px;"><div mc:edit="text_2">Hi, {{$user->name}}</div></td>
								</tr>
								<tr>
									<td class="text-center pb25" style="color:#fff; font-family:'Muli', Arial,sans-serif; font-size:16px; line-height:22px; text-align:left; padding-bottom:25px;">
										<div mc:edit="text_3"> 
											 You are receiving this email because we received a password reset request for your account.
										</div>
									</td>
								</tr>
								<tr mc:hideable>
									<td align="">
										<table class="center" border="0" cellspacing="0" cellpadding="0" style="text-align:left;">
											<tr>
												<td class="pink-button text-button" style="background:#254A45; color:#c1cddc; font-family:'Muli', Arial,sans-serif; font-size:14px; line-height:18px; padding:12px 30px; text-align:center; border-radius:0px 22px 22px 22px; font-weight:bold;"><div mc:edit="text_4"><a href="{{ $resetUrl }}" target="_blank" class="link-white" style="color:#ffffff; text-decoration:none;"><span class="link-white" style="color:#ffffff; text-decoration:none;">Reset Password</span></a></div></td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td class="text-center pb25" style="color:#fff; font-family:'Muli', Arial,sans-serif; font-size:16px; line-height:22px; text-align:left; padding-bottom:25px;"><div mc:edit="text_3">This password reset link will expire in 60 minutes.</div></td>
								</tr>
								<tr>
									<td class="text-center pb25" style="color:#fff; font-family:'Muli', Arial,sans-serif; font-size:16px; line-height:22px; text-align:left; padding-bottom:25px;"><div mc:edit="text_3">If you did not request a password reset, no further action is required.</div></td>
								</tr>
								<tr>
									<td class="text-center pb25" style="color:#fff; font-family:'Muli', Arial,sans-serif; font-size:16px; line-height:22px; text-align:left; padding-bottom:25px;"><div mc:edit="text_3">Best regards, <br>
               
									{{ config('app.name')}}</div></td>
								</tr>
								<tr>
									<td class="text-center pb25" style="color:#fff; font-family:'Muli', Arial,sans-serif; font-size:16px; line-height:22px; text-align:left; padding-bottom:25px;"><div mc:edit="text_3">If you’re having trouble clicking the link, copy and paste the URL below into your web browser:</div></td>
								</tr>
								<tr>
									<td class="text-center pb25" style="color:#fff; font-family:'Muli', Arial,sans-serif; font-size:16px; line-height:22px; text-align:left; padding-bottom:25px;"><div mc:edit="text_3">{{$resetUrl}}</div></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
@endsection