<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DocumentService;
use App\Models\Document;
use Carbon\Carbon;

use function PHPUnit\Framework\isNull;

class DocumentController extends Controller
{
    /** @var  \Illuminate\Http\Request  */

    protected $request;

    /**
     * The var associated with the DocumentService.
     * @var \App\Services\DocumentService  */

    protected $documentServices;

    /**
     * The var associated with the model.
     *
     * @var \App\Models\Document;
     */
    protected  $document;

    /**Se inyectan las dependencias en el constructor  */
    public function __construct(Request $request, DocumentService $documentServices, Document $documentModel)
    {
        $this->documentServices = $documentServices;
        $this->request = $request;
        $this->document = $documentModel;
    }
    public function index()
    {
        /**Se recupera todos los documentos para mostrar en la vista view_documents   */
        $documents = $this->document::all();
        return view('document.view_documents', compact('documents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {   /*Se obtienen los tipos de documentos*/
        $typesDocuments = $this->documentServices->getDocumentTypes();
        /**Se obtiene los tipos de proceso*/
        $processDocuments = $this->documentServices->getProcess();
        /*Se retornan las variables a la vista*/
        return view('document.register_document', compact(['typesDocuments', 'processDocuments']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store()

    {
        /**Se valida los datos recibidos */
        $data = $this->request->validate([
            'name' => 'required|string',
            'tip_id' => 'required|int',
            'pro_id' => 'required|int',
            'file' => 'required|file',
            'doc_contenido' => 'required|string'
        ]);
        /**Sí la variable $data devuelve verdadero se procede a guardar el archivo en la ruta /documentos/$prefijo_de_tipo_de_documento y se registran los datos y la ruta del archivo en la base de datos */
        if ($data) {
            /*Se obtiene el archivo*/
            $file = $this->request->file('file');
            $extension = $file->getClientOriginalExtension();
            $ruta = $this->documentServices->saveFile($data['name'], $file, $data['tip_id'], $data['pro_id'], $extension);
            /**Se genera el codigo de registro */
            $code = $this->documentServices->generateCode($data['tip_id'], $data['pro_id']);
            /**Se guarda los datos en la base de datos*/
            $newRecord = $this->document->newInstance();
            $newRecord->doc_nombre = $data['name'];
            $newRecord->doc_codigo = $code;
            $newRecord->doc_contenido = $data['doc_contenido'];
            $newRecord->doc_path = $ruta;
            $newRecord->doc_id_tipo = $data['tip_id'];
            $newRecord->doc_id_proceso = $data['pro_id'];
            $newRecord->save();
            return redirect(route('document.create'))->with(['create' => 'Documento guardado correctamente', 'slug' => $newRecord->slug]);
        } else {
            return redirect()->back()->withErrors($data);
        }
    }
    //


    /**
     * Display the specified resource.
     */
    public function show(Carbon $carbon, string $slug)
    {
        /*Se obtiene el documento con el slug especificado en el parametro*/
        $document = $this->document::where('slug', $slug)->first();
        /*Se Formatea la fecha de creación del documento*/
        $newDate = $carbon::parse($document->created_at);
        $newDate = $newDate->format('d/m/Y');
        return view('document.show_document', compact(['document', 'newDate']));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        /*Se obtienen los tipos de documentos*/
        $typesDocuments = $this->documentServices->getDocumentTypes();
        /**Se obtiene los tipos de proceso*/
        $processDocuments = $this->documentServices->getProcess();
        /**Se obtiene el documento a editar */
        $document = $this->document::select('doc_documento.*', 'tip_tipo_doc.tip_nombre', 'pro_proceso.pro_nombre')
            ->join('tip_tipo_doc', 'doc_documento.doc_id_tipo', '=', 'tip_tipo_doc.tip_id')
            ->join('pro_proceso', 'doc_documento.doc_id_proceso', '=', 'pro_proceso.pro_id')
            ->where('doc_id', $id)
            ->first();
        /**Se toma el ultimo elemento del array del path separado por '/'*/
        $pathDocument = explode('/', $document->doc_path);
        $nameDocument = end($pathDocument);
        return view('document.edit_document', compact(['document', 'typesDocuments', 'processDocuments', 'nameDocument']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(string $id)
    {
        /**Se valida los datos recibidos */

        $data = $this->request->validateWithBag('post', [
            'name' => 'required|string',
            'tip_id' => 'required|int',
            'pro_id' => 'required|int',
            'doc_contenido' => 'required|string'
        ]);

        if ($data) {
            /**Se obtiene el archivo a guardar */
            $file = $this->request->file('file');
            /**Se genera el codigo de registro */
            $codigo = $this->document::select('doc_codigo', 'doc_id_tipo', 'doc_id_proceso')->where('doc_id', $id)->first();
            $code = $this->documentServices->generateCode(intval($data['tip_id']), intval($data['pro_id']));
            if ($codigo->doc_id_tipo == $data['tip_id'] && $codigo->doc_id_proceso == $data['pro_id']) {
                $code = $codigo->doc_codigo;
            }

            if (!isNull($file)) {
                /**Se valida la existencia del archivo y se elimina para evitar duplicados*/
                $this->documentServices->deleteDocument($id);
                /**Se obtiene la extensión de el archivo a guardar */
                $extension = $file->getClientOriginalExtension();
                /*Se guarda el archivo y se crea la ruta para guardar en la base de datos*/
                $ruta = $this->documentServices->saveFile($data['name'], $file, intval($data['tip_id']), intval($data['pro_id']), $extension);
            }
            /**Se actualizan los datos para el id enviado cómo parametro */
            $updateRecord = $this->document::findOrFail($id);
            $updateRecord->doc_nombre = $data['name'];
            $updateRecord->doc_codigo = $code;
            if (isset($ruta)) {
                $updateRecord->doc_path = $ruta;
            } else {
                $ruta = $this->documentServices->updateRouteDocument($id, $data['tip_id'], $data['pro_id']);
                $updateRecord->doc_path = $ruta;
            }
            $updateRecord->doc_contenido = $data['doc_contenido'];
            $updateRecord->doc_id_tipo = intval($data['tip_id']);
            $updateRecord->doc_id_proceso = intval($data['pro_id']);
            $updateRecord->save();
        }
        return redirect()->route('document.index')->with('success', 'El documento con id ' . $updateRecord->doc_id . ' actualizó correctamente.');
    }

    public function destroy(string $id)
    {
        /**Se elimina el documento del storage */
        $this->documentServices->deleteDocument($id);
        /** */
        $codes = $this->document::select('doc_codigo')->where('doc_id', $id)->first();

        $codes = explode('-', $codes->doc_codigo);

        $prefix = $codes[0] . '-' . $codes[1];
        $this->documentServices->updateCode($prefix, $id);

        /**Se elimina el registro de el documento en la base de datos */


        return redirect('document')->with('delete', 'Registro eliminado exitosamente.');
    }

    function downloadDocument($id)
    {
        /**Se busca ruta para poder descargar el archivo */
        $file = $this->document::select('doc_path')->where('doc_id', $id)->first();
        return response()->download(storage_path('app/' . $file->doc_path));
    }
    /**Función para buscar un archivo especifico */
    public function search()
    {

        /**Se capturan los parametros, si retorna verdadero valida si existe un archivo con los parametro solicitados si no existen, muestra un mesaje de no encontrado, si el parametro es false muestra todos los documentos  */
        if ($this->request->get('search')) {
            $documents = $this->document::where('doc_nombre', 'like', '%' . request('search') . '%')->exists();
            if (!$documents) {
                $documents_fail = "No se encontro el documento.";
                return view('document.view_documents')->with('documents_fail', $documents_fail);
            } else {
                $documents = $this->document::where('doc_nombre', 'like', '%' . request('search') . '%')->get();
            }
        } else {
            $documents = $this->document::all();
        }
        return view('document.view_documents')->with('documents', $documents);
    }
}