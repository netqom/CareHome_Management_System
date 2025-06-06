@if (!$records->isEmpty())

    @foreach ($records as $key => $record)

        <tr>

            <!--td><input type="checkbox" class="i-checks" name="input[]"></td-->

            <td>{{ $key + 1 }}</td>

			<td>{{$record->name}}</td>

			<td>{{ $record->email }}</td>

            <td>{{ $record->phone_number }}</td>

			

			

            <td>
                @php
					$cls_name = 'label-primary btn-primary';
					$text = 'Active';
                    $tooltip = "Make in-active user";
					if ($record->status == 0 && $record->deleted_at == NULL) {
                        $text = 'Inactive';
                        $cls_name = 'label-warning btn-warning';
                        $tooltip = "Make active user";
                    }else if($record->status == 0 && $record->deleted_at != NULL){
						$text = 'Deleted';
                        $cls_name = 'label-danger btn-danger';
					}

                @endphp
				@if($text == 'Deleted')
				<button type="button" class="btn btn-sm {{ $cls_name }} " > {{ $text }}</button>
				@else
                <button type="button" data-toggle="tooltip" data-placement="top" title="{{$tooltip}}" class="btn btn-sm change_status {{ $cls_name }} change_status" data-item-id="{{ $record->id }}" data-status="{{ $record->status }}" data-item-type="user"> {{ $text }}</button>
				@endif
            </td>

            

        </tr>

    @endforeach

@else

    <tr>

        <td class="text-warning text-center" colspan="8">No Data Found</td>

    </tr>

@endif

						