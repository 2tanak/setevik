


@if (count($error) > 0)
    <div class="alert alert-danger fade in ">
        @php
		$str='';
            foreach ($error->all() as $err){
               $str.= '<div>'.$err.'</div>';
            }
		@endphp
          <i style='cursor:pointer;z-index:100' class="fa-fw fa fa-times pull-right my_closse"></i>
           <strong class="text-left">{!! $str !!}</strong>
           <br>
    </div>
	
@endif
