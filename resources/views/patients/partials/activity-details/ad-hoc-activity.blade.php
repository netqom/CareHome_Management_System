@php
$activity_form = getShiftLogField(5); 
foreach ($activity_form[0]['subchilds'] as $key => $subchild) {
			if (strcasecmp($subchild['slug'], 'medication') === 0) {
				unset($activity_form[0]['subchilds'][$key]);
			}
		}
		
		
	 $shift_activity = $activity_form[0]->subchilds->toArray();
   $fieldMap = [];
   $fieldMapSlug = [];
foreach ($shift_activity as $field) {
    $fieldMap[$field['id']] = $field['slug'];
    $fieldMapSlug[$field['slug']] = $field['id'];
}
$adHocData=[];
if(!empty($log))
{
$adHocData = getAdHocShiftLog($log->id);
}

@endphp
<div class="ibox">
            <hr>
              <div class="ibox-content">

                        <!-- <h2 class="fw-600 green">Ad-hoc Activity</h2> -->   
                @if(!empty($adHocData) && count($adHocData) > 0)
                     
                       @foreach ($adHocData as $item)
                        
                         <div class="row mt-4">
                     
                     <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="single-ad-hoc">
                          <h3>{{$item->title}}</h3>
                          <div class="comment bg-light p-4">
                            <p class="mb-0">{{$item->comment}}</p>
                          </div>
                          <div class="d-flex align-items-center justify-content-between mt-3 fw-600">
                            @php 
                           
                              $log_created_by = \App\Models\PatientActivity::where(['log_id' => $log->id, 'id' => $item->activity_id])->first();
                          
                          @endphp
                            <p class="time mb-0"><i class="fa fa-clock-o" aria-hidden="true"></i> <span>{{date('h:i a',strtotime($log_created_by->created_at))}}</span></p>
                            <p class="posted-by mb-0"><i class="fa fa-user" aria-hidden="true"></i> <span>Posted By: {{$log_created_by->added_by->name}}</span></p>
                          </div>
                        </div>
                     </div>

              </div>

               <hr>
               @endforeach
            @else
           
               <div>
              <p>No Ad-hoc Activity yet</p>
            </div>
          @endif
               @if(!empty($log))
               <div class="row mb-3 py-3">
                @php 
                
                   $imgArr = $log->images->toArray();
                  $imgData = array_filter($imgArr, function($item) {
                    return $item['shift_id'] === 5;
                  });
                @endphp
                @foreach($imgData as $key => $img)
                  <div class="col-auto px-2 mb-3">
                    <img src="{{$img['image_url']}}" width="100px" height="100px" alt="img"/>
                  </div>
                @endforeach
              </div>
              @endif

  </div>
</div>