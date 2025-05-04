@extends('adminlte::page')

@section('title', $title . ' | SIMGUDANG')

@section('meta_tags')
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}" />
@endsection

@section('content_header')
    <h1>{{ $title }}</h1>
@stop

@section('content')
    {{ $slot }}
@stop

@section('css')
{{-- font awesome --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  
{{ $style ?? '' }}
@stop

@section('js')
{{-- font awesome --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/js/all.min.js" integrity="sha512-b+nQTCdtTBIRIbraqNEwsjB6UvL3UEMkXnhzd8awtCYh0Kcsjl9uEgwVFVbhoj3uu1DO1ZMacNvLoyJJiNfcvg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</script>

  {{ $script ?? '' }}
@stop