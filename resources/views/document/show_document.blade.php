@extends('layouts.app')

@section('content')
<div class="container">
    <div class="info_file">
        <h1>{{$document->doc_nombre}}</h1>
        <div class="container_file">
            <div>
                <p>Codigo:</p>
                {{$document->doc_codigo}}
            </div>
            <div>
                <p>Creado</p>
                {{$newDate}}
            </div>
            <div>
                <p>Descargar</p>
                <a href="{{route('download',$document->doc_id)}}"><img class="icon_download" src="{{Vite::asset('resources/img/download.png') }}" alt=""></a></td>
            </div>
            
        </div>
        <div class="description_document">
            <div>
                <h2>Descripción</h2>
                <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Laborum eveniet incidunt pariatur sint officia, accusantium assumenda cum fugit mollitia ipsam dolor neque atque praesentium quae ab dolores quo cumque ea!</p>
            </div>
        </div>
    </div>
</div>
@endsection