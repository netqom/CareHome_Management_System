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
									<td class="pb25" style="color:#ffffff; font-family:'Muli', Arial,sans-serif;text-align:left; font-size: 14px; padding-bottom:25px;">
                                    <div mc:edit="text_2">
                                        <p style="font-weight: bold;">Dear, {{ $data['user_name'] }},</p> 
                                        <p>Your care home {{$data['home_name']}} subscription will expire in 7 days.</p>
                                    </div></td>
								</tr>
								{{-- @if($data['user_type'] == 'new') --}}
									<tr>
										<td class="text-center pb25" style="color:#fff; font-family:'Muli', Arial,sans-serif; font-size:16px; line-height:22px; text-align:left; padding-bottom:25px;">
											<div mc:edit="text_3"> 
                                                <b>End date:</b> {{ date('Y-m-d', strtotime($data['end_date'])) }} <br>
                                                
											</div>
										</td>
									</tr>
								{{-- @endif --}}
								{{-- <tr>
									<td class="text-center pb25" style="color:#fff; font-family:'Muli', Arial,sans-serif; font-size:16px; line-height:22px; text-align:left; padding-bottom:25px;"><div mc:edit="text_3">You have been added as care home admin please proceed with login</div></td>
								</tr> --}}
								<!-- Button -->
								<tr mc:hideable>
									<td align="">
										<table class="center" border="0" cellspacing="0" cellpadding="0" style="text-align:left;">
											<tr>
												<td class="pink-button text-button" style="background:#254A45; color:#c1cddc; font-family:'Muli', Arial,sans-serif; font-size:14px; line-height:18px; padding:12px 30px; text-align:center; border-radius:0px 22px 22px 22px; font-weight:bold;"><div mc:edit="text_4"><a href="{{ route('homes.show', $data['home_id']) }}" target="_blank" class="link-white" style="color:#ffffff; text-decoration:none;"><span class="link-white" style="color:#ffffff; text-decoration:none;">View Details</span></a></div></td>
											</tr>
										</table>
									</td>
								</tr>
								<!-- END Button -->
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
@endsection