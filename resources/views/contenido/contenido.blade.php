@extends('principal')
@section('contenido')

    @if(Auth::check())
        @if(Auth::user()->id_rol == 1)
            <template v-if=menu==0>
                    <nota></nota>
            </template>
        @else
        @endif
    @endif

@endsection