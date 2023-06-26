<?php

namespace App\Services;

use App\Models\Document;
use App\Models\DocumentType;
use App\Models\Process;
use Illuminate\Support\Facades\Storage;

class DocumentService
{

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

  /**
   * The var associated with the storage.
   *
   * @var \Illuminate\Support\Facades\Storage;
   */
  protected  $storage;

  /**Se inyectan las dependencias */
  public function __construct(Storage $storage, Document $documentModel, DocumentType $documenTypeModel, Process $documentProcessModel)
  {

    $this->document = $documentModel;
    $this->documenType = $documenTypeModel;
    $this->documentProcess = $documentProcessModel;
    $this->storage = $storage;
  }

  /**Funcion para  obtener los tipos de documento del modelo DocumentTypes*/
  public function getDocumentTypes(int $tip_id = null)
  {
    /** Si el parametro tip_id está defino se retorna el documento basado en el parametro pasado si no se retornan todos los documentos  */
    if (!isset($tip_id)) {
      $element =  $this->documenType::all();
      return $element;
    }
    $element = $this->documenType::where('tip_id', $tip_id)->get();
    return $element[0];
  }

  /**Funcion para  obtener los tipos de documento del modelo DocumentTypes*/
  public function getProcess(int $pro_id = null)
  {
    if (!isset($pro_id)) {
      $element =  $this->documentProcess::all();
      return $element;
    }
    $element = $this->documentProcess::where('pro_id', $pro_id)->get();
    return $element[0];
  }
  /*Se genera el codigo basado en los parametros */
  function getCode($tip_id, $pro_id): string|null
  {
    $prefix_doc = $this->getDocumentTypes($tip_id);
    $prefix_pro = $this->getProcess($pro_id);
    $code = $this->document::select('doc_codigo')->where('doc_codigo', 'like', '%' . $prefix_doc->tip_prefijo . '-' . $prefix_pro->pro_prefijo . '%')->orderBy('doc_codigo', 'desc')->first();
    return $code->doc_codigo ?? $code;
  }

  /**Se genera el prefijo para el codigo basado en los parametros que son los ids*/

  function generatePrefix(int $tip_id, int $pro_id): string|null
  {
    $prefix_doc = $this->getDocumentTypes($tip_id);
    $prefix_pro = $this->getProcess($pro_id);
    $prefix = $prefix_doc->tip_prefijo . '-' . $prefix_pro->pro_prefijo . '-';
    return $prefix;
  }

  /**Se genera el codigo del documento llamando las funciones getCode y generatePrefix si existe se aumenta el número consecutivo*/
  function generateCode(int $tip_id, int $pro_id): string
  {
    $code = $this->getCode($tip_id, $pro_id);
    if ($code) {
      $code = explode('-', $code);
      $num = intval($code[2]) + 1;
      $newCode = $this->generatePrefix($tip_id, $pro_id) . $num;
      return $newCode;
    }
    $newCode = $this->generatePrefix($tip_id, $pro_id) . '1';
    return $newCode;
  }

  /** Funcion para actualizar todos los codigos cuando se elimina un registro con el mismo codgio de documento*/
  function updateCode($prefix, $id)
  {

    $codes = $this->document::where('doc_codigo', 'like', '%' . $prefix . '%')->where('doc_id', '!=', $id)->orderBy('doc_id', 'asc')->get();
    $this->document::destroy($id);
    
    if ($codes) {
      $i = 1;
      foreach ($codes as $key) {
        $num = explode('-', $key->doc_codigo);
        $code = $num[0] . "-" . $num[1] . "-" . $i++;
        $document = $this->document::findOrFail($key->doc_id);
        $document->doc_codigo = $code;
        $document->save();
      }
    }
  }

  /**Funcion para validar el nombre de el archivo en el storage si existe se retorna con un número consecutivo */
  function validateNameFile($path, $tip_id, $pro_id): string
  {
    $path = preg_replace('/[^a-zA-Z0-9.]\//', '_', $path);
    $path = strtolower($path);
    $existPath = "documentos/" . $tip_id . "/" . $pro_id . '/' . $path;
    if ($this->storage::exists($existPath)) {
      $arrPath = explode('.', $path);
      $numDocument = explode('__', $arrPath[0]);
      if (count($numDocument) > 1) {
        $latestNum  = end($numDocument);
        $latestNum++;
        $newName = $numDocument[0] . "__" . strval($latestNum) . "." . $arrPath[1];
        $newName =  $this->validateNameFile($newName, $tip_id, $pro_id);
      } else {
        $newName = $arrPath[0] . '__' . "." . $arrPath[1];
        $newName =  $this->validateNameFile($newName, $tip_id, $pro_id);
      }
      $path = strtolower($newName);
      return $path;
    } else {
      $path = strtolower($path);
      return $path;
    }
  }

  /**Función para guardar el archivo en el estorage y devolver la ruta para guardar en la base de datos */
  function saveFile($name, $file, $tip_id, $pro_id, $extension)
  {
    $tip_idx = intval($tip_id);
    $pro_idx = intval($pro_id);

    /* Se obtiene el prefijo de tipo de documento */
    $tip_prefix = $this->getDocumentTypes($tip_idx)->tip_prefijo;

    /* Se obtiene el prefijo de proceso */
    $pro_prefix = $this->getProcess($pro_idx)->pro_prefijo;

    /* Se formatea el nombre para que quede en minúscula */
    $filename = $name . "." . $extension;

    /** Se valida el nombre del archivo, si ya existe se guarda con un número consecutivo */
    $filename = $this->validateNameFile($filename, $tip_prefix, $pro_prefix);

    /* Se asigna la ruta en la que se guarda el archivo con el formato /documentos/$prefijo_tip_doc/$prefijo_pro */
    $ruta = $file->storeAs("documentos/" . $tip_prefix . "/" . $pro_prefix, $filename);

    return $ruta;
  }

  /**Se muee el documento a la ruta nueva y retorna la nueva ruta para ser guardada */

  function updateRouteDocument($id, $tip_id, $pro_id)
  {

    $tip_idx = intval($tip_id);
    $pro_idx = intval($pro_id);

    /* Se obtiene el prefijo de tipo de documento */
    $tip_prefix = $this->getDocumentTypes($tip_idx)->tip_prefijo;

    /* Se obtiene el prefijo de proceso */
    $pro_prefix = $this->getProcess($pro_idx)->pro_prefijo;

    $path = $this->document::select('doc_path')->where('doc_id', $id)->first();
    $filename = explode('/', $path->doc_path);
    /** Se valida el nombre del archivo, si ya existe se guarda con un número consecutivo */
    $filename = $this->validateNameFile(end($filename), $tip_prefix, $pro_prefix);
    /* Se asigna la ruta en la que se guarda el archivo con el formato /documentos/$prefijo_tip_doc/$prefijo_pro */
    $newPath = "documentos/" . $tip_prefix . "/" . $pro_prefix . " / " . $filename;
    
    $this->storage::move($path->doc_path, $newPath);

    return $newPath;
  }

  /**Funcion para eliminar el documentos y así evitar duplicados*/
  function deleteDocument($id)
  {
    $path = $this->document::select('doc_path')->where('doc_id', $id)->first();
    if ($path) {
      $this->storage::delete($path->doc_path);
    }
  }
}
