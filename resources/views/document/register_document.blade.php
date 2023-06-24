        @extends('layouts.app')

        @section('content')
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">{{ __('Registrar Documento') }}</div>

                        <div class="card-body">

                            <form class="document_register" action="{{route('document.store')}}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="form_container">
                                
                                    <div class="container_file">
                                        
                                        <div>
                                            <label>Nombre:</label>
                                            <input type="text" id="name" class="from_input" name="name"  maxlength="10">
                                        </div>
                                        <div>

                                                <label>Archivo:</label>
                                                <input type="file" id="file" class="input_file" name="file" required
                                                    maxlength="10 "  accept=".pdf, .doc, .docx" placeholder="Seleccionar archivo">
                                        </div>
                                    </div>
                                    <div class="container_process">
                                        <div>
                                            <label>Tipo de documento:</label>
                                            <select name="tip_id">
                                                <option>- seleccionar-</button></option>
                                                @foreach ($typesDocuments as $result)
                                                <option value="{{$result->tip_id}}">{{$result->tip_nombre }} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label>Proceso:</label>
                                            <select name="pro_id">
                                                <option>- seleccionar -</option>
                                                @foreach ($processDocuments as $result)
                                                <option value="{{$result->pro_id}}">{{$result->pro_nombre }} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="contanier_text_area">
                                        <div class="contanier_text">
                                            <label>Descripción:</label>
                                            <textarea name="doc_contenido" rows="4" cols="40"  maxlength="255"></textarea>
                                        </div>
                                    </div>
                                    @if($errors->any())
            
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
        x
        @endif

                                    @if (session('create'))
                                        {{session('create')}}
                                    @endif
                                    <button name="register" type="submit"
                                        class="form_submit"><strong>REGISTRAR</strong></button>
                                </div>
                            </form>


                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endsection