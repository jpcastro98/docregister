<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DocumentService;
use App\Models\Document;
use App\Models\DocumentType;
use App\Models\Process;
use Carbon\Carbon;

class DocumentController extends Controller
{
    /** @var  \Illuminate\Http\Request description */

    protected $request;

    /** @var \App\Services\DocumentService description */

    protected $documentServices;

    /**
     * The var associated with the model.
     *
     * @var \App\Models\Document;
     */
    protected  $document;

    /**
     * The var associated with the model.
     *
     * @var \App\Models\DocumentType
     */
    protected  $documenType;
    /**
     * The var associated with the model.
     *
     * @var \App\Models\Process
     */
    protected  $documentProcess;



    public function __construct(Request $request, DocumentService $document_service, Document $documentModel, DocumentType $documenTypeModel, Process $documentProcessModel)
    {
        $this->documentServices = $document_service;
        $this->request = $request;
        $this->document = $documentModel;
        $this->documenType = $documenTypeModel;
        $this->documentProcess = $documentProcessModel;
    }
    public function index()
    {

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

            return redirect(route('document.create'))->with('create', 'Documento guardado correctamente');
        } else {
            return redirect()->back()->withErrors($data);
        }
    }
    //


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $document = $this->document::where('slug', $id)->first();
        $newDate = Carbon::parse($document->created_at);
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
        $document = $this->document::where('doc_id', $id)->first();

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
            'file' => 'required|file',
            'doc_contenido' => 'required|string'
        ]);

        if ($data) {

            /**Se valida la existencia del archivo y se elimina para evitar duplicados*/
            $this->documentServices->deleteDocument($id);
            /**Se obtiene el archivo a guardar */
            $file = $this->request->file('file');
            /**Se obtiene el archivo a guardar */
            $extension = $file->getClientOriginalExtension();
            /**Se genera el codigo de registro */
            $code = $this->documentServices->generateCode(intval($data['tip_id']), intval($data['pro_id']));
            /*Se guarda el archivo y se crea la ruta para guardar en la base de datos*/
            $ruta = $this->documentServices->saveFile($data['name'], $file, intval($data['tip_id']), intval($data['pro_id']), $extension);
            /**Se actualizan los datos para el id enviado cómo parametro */
            $updateRecord = $this->document::findOrFail($id);
            $updateRecord->doc_nombre = $data['name'];
            $updateRecord->doc_codigo = $code;
            $updateRecord->doc_path = $ruta;
            $updateRecord->doc_contenido = $data['doc_contenido'];
            $updateRecord->doc_id_tipo = intval($data['tip_id']);
            $updateRecord->doc_id_proceso = intval($data['pro_id']);
            $updateRecord->save();
        }
        return redirect()->route('document.index')->with('success', 'El documento se actualizó correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->documentServices->deleteDocument($id);
        $document = $this->document::destroy($id);
        return redirect('document')->with('delete', 'Registro eliminado exitosamente.');
    }

    function downloadDocument($id)
    {


        /**Se crea ruta para poder descargar el archivo */
        $file = $this->document::select('doc_path')->where('doc_id', $id)->first();
        return response()->download(storage_path('app/' . $file->doc_path));
    }

    public function search()
    {

        if ($this->request->get('search')) {
            $documents = $this->document::where('doc_nombre', 'like', '%' . request('search') . '%')->exists();
            if ($documents == false) {
                $documents_fail = "No se encontro el archivo";
                return view('document.view_documents')->with('documents_fail', $documents_fail);
            }else{
                $documents = $this->document::where('doc_nombre', 'like', '%' . request('search') . '%')->get();
            }
        } else {
            $documents = $this->document::all();
        }
        return view('document.view_documents')->with('documents', $documents);
    }
}
