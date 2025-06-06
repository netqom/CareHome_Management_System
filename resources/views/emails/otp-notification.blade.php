@extends('layouts.email')

@section('content')
	
	<table align="center" border="0" cellpadding="0" cellspacing="0" width="600">
		<tr>
			<td bgcolor="#ffffff" style="padding: 40px 30px 40px 30px;">
				<table border="0" cellpadding="0" cellspacing="0" width="100%" style="font-size: 16px;
				line-height: 1.5;">
					<tr>
						<td style="text-align: center;">
							<h2>Two-Factor Authentication</h2>
						</td>
					</tr>
					<tr>
						<td style="padding: 20px 0;">
							<p>Please use the following One-Time Password (OTP) to authenticate:</p>
							<h1 style="font-size: 48px; font-weight: bold; margin: 0 auto; color: #333333;text-align: center;display: table;background: #f0f0f0;padding: 6px 20px;border-radius: 10px;letter-spacing: 2px;">{{$otp}}</h1>
						</td>
					</tr>
					<tr>
						<td style="padding: 20px 0;">
							<p>This OTP is valid for a single use and should not be shared with anyone. If you did not request this OTP, please ignore this message.</p>
						</td>
					</tr>
					<tr>
						<td style="padding: 20px 0;">
							<p>Thank you,<br>DocRyt</p>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
@endsection