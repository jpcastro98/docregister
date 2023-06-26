@extends('layouts.app')
@section('content')
    <div class="container">

        <div class="row justify-content-center row_document">

            <div class="col-md-8">
                @if (session('success'))
                    {{ session('success') }}
                @endif
                @if (isset($documents_fail))
                    <div class="card view_text">
                        <div class="card-header">{{ __('Lista de documentos') }}</div>
                        <div class="card-body text-document">
                            <div>
                                <h1>{{ $documents_fail }} <a href="{{ route('document.index') }}">regresar.</a></h1>
                            </div>
                        @elseif (count($documents) > 0)
                            <div class="card view_document">
                                <div class="card-header">{{ __('Lista de documentos') }}
                                    <form class="form-search" method="GET" action="{{ route('search') }}">
                                        @csrf
                                        @method('GET')
                                        <div class="search-document">
                                            <input type="search" class="search-document" id="search" name="search">
                                            <button type="submit">Enviar</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-body table-document vie-table">
                                    <table>
                                        <thead>
                                            <tr>
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
                                                    <td data-label="name"><a
                                                            href="{{ route('document.show', $document) }}">{{ $document->doc_nombre }}</a>
                                                    </td>
                                                    <td data-label="code">{{ $document->doc_codigo }}</td>
                                                    <td data-label="code">{{ $document->doc_contenido }}</td>
                                                    <td data-label="path"><a
                                                            href="{{ route('download', $document->doc_id) }}"><img
                                                                class="icon"
                                                                src="{{ Vite::asset('resources/img/download.png') }}"
                                                                alt=""></a></td>
                                                    <td data-label="edit">

                                                        <form action="{{ route('document.destroy', $document->doc_id) }}"
                                                            method="POST">
                                                            <a class="btn btn-primary"
                                                                href="{{ route('document.edit', $document->doc_id) }}"><img
                                                                    class="icon"
                                                                    src="{{ Vite::asset('resources/img/edit.png') }}"></a>
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger"><img
                                                                    class="icon"
                                                                    src="{{ Vite::asset('resources/img/delete.png') }}"></button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <div class="card view_text">
                                        <div class="card-header">{{ __('Lista de documentos') }}</div>
                                        <div class="card-body text-document">
                                            <div>
                                                <h1>Bienvenido {{ Auth::user()->name }}, <a
                                                        href="{{ route('document.create') }}">aquí</a> podras ver todos los
                                                    documentos y podras registrar los tuyos.</h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                @endif
            </div>
        </div>
    </div>
@endsection
