

        <div class="ibox">
        @if(count($logAttachmentMorningShift) > 0)
            <hr>
              <div class="ibox-content">

                        <h3 class="fw-600">Morning Attachment</h3>

                        <div class="row mt-4">
                           
                                @foreach($logAttachmentMorningShift as $morningShift)
                                <div class="col-lg-2 col-md-3 col-sm-6 col-6">
                                    <div class="pdf-attachments my-1">
                                        <a class="py-2 px-2 bg-light d-block text-center" href="{{asset($morningShift->image)}}" download target="_blank" title="" data-gallery="">
                                            <img class="mw-100" src="{{asset('assets/img/download-pdf-icon.png')}}"></a>                            
                                    </div>
                                </div>
                                @endforeach
                            
                            

                        </div>

                </div>
                @endif 
                <!-- afternoon attachments -->
                @if(count($logAttachmentAfternoonShift) > 0)
                <hr>
              <div class="ibox-content">

                        <h3>Afternoon Attachment</h3>

                   <div class="row mt-4">
                   
                        @foreach($logAttachmentAfternoonShift as $afternoonShift)
                            <div class="col-lg-2 col-md-3 col-sm-6 col-6">
                                <div class="pdf-attachments my-1">
                                    <a class="py-2 px-2 bg-light d-block text-center" href="{{asset($afternoonShift->image)}}" download target="_blank" title="" data-gallery=""><img class="mw-100" src="{{asset('assets/img/download-pdf-icon.png')}}"></a>                            
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
                @endif

            <!-- evening attachments -->
            @if(count($logAttachmentEveningShift) > 0)
                <hr>
              <div class="ibox-content">

                        <h3>Evening Attachment</h3>

                   <div class="row mt-4">
                  
                   @foreach($logAttachmentEveningShift as $eveningShift)
                     <div class="col-lg-2 col-md-3 col-sm-6 col-6">
                        <div class="pdf-attachments my-1">
                            <a class="py-2 px-2 bg-light d-block text-center" href="{{asset($eveningShift->image)}}" download target="_blank" title="" data-gallery=""><img class="mw-100" src="{{asset('assets/img/download-pdf-icon.png')}}"></a>                            
                        </div>
                     </div>
                     @endforeach
                    
                    
                    </div>

                </div>
                @endif
                <!-- evening attachments -->
                @if(count($logAttachmentNightShift) > 0)
                <hr>
              <div class="ibox-content">

                        <h3>Night Attachment</h3>

                   <div class="row mt-4">
                   
                   @foreach($logAttachmentNightShift as $nightShift)
                     <div class="col-lg-2 col-md-3 col-sm-6 col-6">
                        <div class="pdf-attachments my-1">
                            <a class="py-2 px-2 bg-light d-block text-center" href="{{asset($nightShift->image)}}" download target="_blank" title="" data-gallery=""><img class="mw-100" src="{{asset('assets/img/download-pdf-icon.png')}}"></a>                            
                        </div>
                     </div>
                     @endforeach
                    
                    
                    </div>

                </div>
                @endif
                <!-- Ad_Hoc attachments -->
                @if(count($logAttachmentAdHocShift) > 0)
                <hr>
              <div class="ibox-content">

                        <h3>Ad-hoc Attachment</h3>

                   <div class="row mt-4">
                   
                   @foreach($logAttachmentAdHocShift as $adhocShift)
                     <div class="col-lg-2 col-md-3 col-sm-6 col-6">
                        <div class="pdf-attachments my-1">
                            <a class="py-2 px-2 bg-light d-block text-center" href="{{asset($adhocShift->image)}}" download target="_blank" title="" data-gallery=""><img class="mw-100" src="{{asset('assets/img/download-pdf-icon.png')}}"></a>                            
                        </div>
                     </div>
                     @endforeach
                    </div>

                </div>
                @endif

        @if(count($logAttachmentNightShift) == 0 && count($logAttachmentEveningShift) == 0 && count($logAttachmentAfternoonShift) == 0 && count($logAttachmentMorningShift) == 0)
        <div class="ibox-content">
            <div>
                <p>No Attachment Found</p>
            </div>
        </div>
        @endif                
        </div>

