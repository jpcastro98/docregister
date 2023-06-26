@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Editar documento') }}</div>

                    <div class="card-body">
                        @if (session('success'))
                            {{ session('success') }}
                        @endif
                        <form action="{{ route('document.update', $document->doc_id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="form_container">
                                <div class="container_file">
                                    <div>
                                        <label>Nombre:</label>
                                        <input type="text" id="name" class="from_input" name="name" required
                                            maxlength="60" value="{{ $document->doc_nombre }}">
                                    </div>
                                    <div>

                                        <label>Archivo:</label>
                                        <a href="{{ route('download', $document->doc_id) }}">
                                            {{ $nameDocument }}
                                        </a>
                                        <input type="file" id="file" class="input_file"
                                            name="file"{{ isset($nameDocument) ? '' : 'required' }} }}
                                            accept=".pdf, .doc, .docx" placeholder="Seleccionar archivo">

                                    </div>
                                </div>
                                <div class="container_process">
                                    <div>
                                        <label>Tipo de documento:</label>
                                        <select name="tip_id">
                                            <option value="{{ $document->doc_id_tipo }}" selected>{{ $document->tip_nombre }}</option>
                                            @foreach ($typesDocuments as $result)
                                                <option value="{{ $result->tip_id }}">{{ $result->tip_nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label>Proceso:</label>
                                        <select name="pro_id">
                                            <option value="{{ $document->doc_id_proceso }}" selected>{{ $document->pro_nombre }}</option>
                                            @foreach ($processDocuments as $result)
                                                <option value="{{ $result->pro_id }}">{{ $result->pro_nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>                                    
                                </div>
                                <div class="contanier_text_area">
                                    <div class="contanier_text">
                                        <label>Descripción:</label>
                                        <textarea name="doc_contenido" rows="4" cols="40" required maxlength="255" value="">{{$document->doc_contenido}}</textarea>
                                    </div>
                                </div>
                                @if ($errors->any())
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                @endif
                                <button name="edit" type="submit" class="form_submit"><strong>EDITAR</strong></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
