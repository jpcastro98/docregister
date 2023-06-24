@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center row_document">
        <div class="col-md-8">
            @if (count($documents)>0)
             <div class="card view_document">
                <div class="card-header">{{ __('Lista de documentos') }}</div>
                <div class="card-body table-document vie-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Nombre</th>
                                <th>Codigo</th>
                                <th>Descripcion</>
                                <th>Descargar</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="myTable">    
                            @foreach ($documents as $document)
                            <tr>
                                <td>{{$document->doc_id}}</td>
                                <td data-label="name"><a href="{{route('document.show',$document->doc_id)}}">{{ $document->doc_nombre }}</a></td>
                                <td data-label="code">{{ $document->doc_codigo }}</td>
                                <td data-label="code">{{ $document->doc_contenido }}</td>
                                <td data-label="path"><a href="{{route('download',$document->doc_id)}}"><img class="icon_download" src="{{Vite::asset('resources/img/download.png') }}" alt=""></a></td>
                                <td data-label="edit">
                                    
                                    <form action="{{ route('document.destroy',$document->doc_id) }}" method="POST">                       
                                        <a class="btn btn-primary" href="{{ route('document.edit',$document->doc_id) }}"><img class="icon_download" src="{{Vite::asset('resources/img/edit.png') }}"></a>
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger"><img class="icon_download" src="{{Vite::asset('resources/img/delete.png') }}" ></button>
                                    </form>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <div class="card view_text">
                        <div class="card-header">{{ __('Lista de documentos') }}</div>
                    <div class="card-body text-document">
                        <div>
                            <h1>Bienvenido {{Auth::user()->name}}, <a href="{{route('document.create')}}">aquí</a> podras ver todos los documentos y podras registrar los tuyos.</h1>
                        </div>
                    @endif
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
