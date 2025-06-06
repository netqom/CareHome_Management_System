<!DOCTYPE html>
<html lang="en">

<head>
<link rel="icon" href="{{ url('fav-icon.png') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Incident</title>
</head>

<body>
    <table cellpadding="5"
        style="width: 700px;margin: 0 auto;border: 1px solid #a6a6a6;font-size: 14px;font-family: Arial, sans-serif;border-collapse: collapse;color: #484848;"
        cellspacing="0">
        <tbody>
            <tr>
                <td style="width: 110px;padding: 10px;">
                    <img style="width: 110px;" src="{{$patient->care_home->image_path}}" alt="" title="">
                </td>
                <td style="text-align: center;">
                    <h1 style="margin-bottom: 5px; font-size: 22px;">{{$patient->care_home->name}}</h1>
                    <p style="margin-top: 0;">{{$patient->care_home->street.', '.$patient->care_home->city.', '.$patient->care_home->state.', '. $patient->care_home->zip_code}}</p>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="border: 1px solid #a6a6a6;">
                    <p style="margin: 5px;"><strong style="text-decoration: underline;">INSTRUCTIONS</strong>: Provider, Resident Manager, or caregiver completes a log entry whenever there is an injury or accident involving a resident. Keep this log in the Adult Family Home in a readily accessible area.</p>
                </td>
            </tr>
			
            <tr>
                <td colspan="2">
                    <table style="width: 100%;margin-bottom: 20px;margin-top: 10px;">
                        <tbody>
                            <tr>
                            
                                <td style="width: 110px;">  <img style="width: 110px; border: 1px solid #d3d3d3;border-radius: 4px;" src="{{$patient->profile_image_path}}" alt="" title=""></td>
                                <td style="padding-left: 20px;">
                                    <h2 style="margin: 0 0 12px;color: #1ab394;font-size: 18px;">{{$patient->name}}</h2>
                                    <div style="margin-bottom: 15px;font-size: 13px;">
										<span style="margin-right: 20px;"><img style="width: 18px;vertical-align: top; margin-top: 2px;" src="{{asset('assets/img/phone-icon.png')}}" alt="" title=""> {{ $patient->phone }}</span>
                                        <span style="margin-right: 20px;"><img style="width: 15px;vertical-align: top; margin-top: 5px; margin-right: 5px;" src="{{asset('assets/img/email-icon.png')}}" alt="" title=""> {{ $patient->email }}</span>
										<br><span style="margin-top: 10px; display: block;"><img style="width: 18px;vertical-align: middle;" src="{{asset('assets/img/location-icon.png')}}" alt="" title=""> {{$patient->address}}</span>
                                    </div>
                                    <div style="margin-bottom: 30px;">
                                        <div class="box"
                                            style="border: 1px dashed #a6a6a6;border-radius: 2px;padding: 3px 5px;float: left;width: auto;margin-right: 20px;margin-bottom: 0px;min-width: 100px;">
                                            <strong style="font-size: 14px;">{{ date('M d, Y', strtotime($patient->admission_date)) }}</strong>
                                            <p style="margin: 0px 0 0;color: #7d7d7d;font-size: 14px;">Admitted on </p>
                                        </div>
                                       @if($patient->deleted_at != NULL)
										<div class="box"
                                            style="border: 1px dashed #a6a6a6;border-radius: 2px;padding: 3px 5px;float: left;width: auto;margin-right: 20px;margin-bottom: 0px;min-width: 100px;">
                                            <strong style="font-size: 14px;">{{ date('M d, Y', strtotime($patient->deleted_at)) }}</strong>
                                            <p style="margin: 0px 0 0;color: #7d7d7d; font-size: 14px;">Archived on </p>
                                        </div>
                                        @endif
                                        @if($patient->discharged == 1)
										<div class="box"
                                            style="border: 1px dashed #a6a6a6;border-radius: 2px;padding: 3px 5px;float: left;width: auto;margin-right: 20px;margin-bottom: 0px;min-width: 100px;">
                                            <strong style="font-size: 14px;">{{ date('M d, Y', strtotime($patient->updated_at)) }}</strong>
                                            <p style="margin: 0px 0 0;color: #7d7d7d; font-size: 14px;">Discharged on </p>
                                        </div>
                                        @endif
                                       
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            @if(!$incident->isEmpty())
            @foreach($incident as $report)
            <tr>
                <td colspan="2">
                    <table style="width: 100%;border: 1px solid #a6a6a6;" cellspacing="0" cellpadding="6">
                        <tbody>
                            <tr style="background: #333;color: #fff;">
                                <th style="padding: 5px;border-right: 1px solid #828282;font-weight: normal;">Date & Time</th>
								{{-- <th style="padding: 5px;border-right: 1px solid #828282;font-weight: normal;">Time</th> --}}
                                <th style="padding: 5px;border-right: 1px solid #828282;font-weight: normal; width: 120px;">Incident Title</th>
                                <th style="padding: 5px;border-right: 1px solid #828282;font-weight: normal; width: 180px;">Reported to Whom</th>
                                <th style="padding: 5px;font-weight: normal;">Reported By</th>
                            </tr><tr>
                                <td valign="top" style="border-right: 1px solid #a6a6a6; text-align: center; white-space: nowrap;">{{ date('M d, Y H:i A', strtotime($report->created_at)) }}
                                </td>
								{{-- <td style="border-right: 1px solid #a6a6a6;text-align: center; white-space: nowrap;">{{ date('H:i A', strtotime($report->created_at)) }}
                                </td> --}}
                                <td valign="top" style="border-right: 1px solid #a6a6a6;page-break-inside: auto;">{{$report->title}}</td>
                                <td valign="top" style="border-right: 1px solid #a6a6a6; width:300px;page-break-inside: auto;">
								<p style="margin: 0 0 5px;">
                                <?php
                                $values = [];
                                if ($report->report_to_nine) {
                                    $values[] = '911';
                                }
                                if ($report->report_to_rcs) {
                                    $values[] = 'RCS';
                                }
                                if ($report->report_to_manager) {
                                    $values[] = 'Manager';
                                }?>
                                {{implode(', ', $values)}}</p>
                                @if($report->report_to_other)
								<p style="margin: 0 0 5px; font-size: 13px;"><strong>Other:</strong> {{$report->other}} </p>
                                @endif
								</td>
                                <td valign="top" style="text-align: center;">
                                    {{$report->added_by->name}}</td>                                 
                            </tr><tr>
                                <td valign="top" colspan="4" style="border-top: 1px solid #a6a6a6;">
                                    <div>
                                        <p style="margin: 0; font-size: 14px;">
                                            <strong>Incident Detail Description:</strong> {{$report->incident_reason}}
                                        </p>
                                    </div>
                                </td>
                            </tr><tr>
                                <td valign="top" colspan="4" style="border-top: 1px solid #a6a6a6;">
                                    <div>
                                        <p style="margin: 0; font-size: 14px;">
                                            <strong>Incident Action Taken:</strong> {{$report->action_taken}}
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            @endforeach
            @endif
        </tbody>
    </table>
</body>

</html>