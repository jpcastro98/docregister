<?php

namespace App\Services;

use App\Models\Document;
use App\Models\DocumentType;
use App\Models\Process;
use Illuminate\Support\Facades\Storage;

use function PHPUnit\Framework\isNull;

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
   * The var associated with the model.
   *
   * @var \Illuminate\Support\Facades\Storage;;
   */
  protected  $storage;


  public function __construct(Storage $storage, Document $documentModel, DocumentType $documenTypeModel, Process $documentProcessModel)
  {

    $this->document = $documentModel;
    $this->documenType = $documenTypeModel;
    $this->documentProcess = $documentProcessModel;
    $this->storage = $storage;
  }

  public function getDocumentTypes(int $tip_id = null): object
  {
    if (!isset($tip_id)) {
      $element =  $this->documenType::all();
    } else {
      $element = $this->documenType::where('tip_id', $tip_id)->get();
      return $element[0];
    }
    return $element;
  }

  public function getProcess(int $pro_id = null): object
  {
    if (!isset($pro_id)) {
      $element =  $this->documentProcess::all();
    } else {
      $element = $this->documentProcess::where('pro_id', $pro_id)->get();
      return $element[0];
    }
    return $element;
  }

  function getCode($tip_id, $pro_id): string|null
  {
    $prefix_doc = $this->getDocumentTypes($tip_id);
    $prefix_pro = $this->getProcess($pro_id);
    $document = $this->document::where('doc_codigo', 'like', '%' . $prefix_doc->tip_prefijo . '-' . $prefix_pro->pro_prefijo . '%')->orderBy('doc_id', 'desc')->first();
    return $document;
  }

  function generatePrefix(int $tip_id, int $pro_id): string|null
  {
    $prefix_doc = $this->getDocumentTypes($tip_id);
    $prefix_pro = $this->getProcess($pro_id);
    $prefix = $prefix_doc->tip_prefijo . '-' . $prefix_pro->pro_prefijo . '-';
    return $prefix;
  }
  function generateCode(int $tip_id, int $pro_id): string
  {
    $code = $this->getCode($tip_id, $pro_id);
    if ($code) {
      $code = explode('-', $code);
      $num = intval($code[2]) + 1;
      $newCode = $this->generatePrefix($tip_id, $pro_id) . $num;
      return $newCode;
    } else {
      $newCode = $this->generatePrefix($tip_id, $pro_id) . '1';
      return $newCode;
    }
  }

  function validateNameFile($path,$tip_id,$pro_id): string
  {

    $path = preg_replace('/[^a-zA-Z0-9.]\//', '_', $path);
    $path = strtolower($path);
    $existPath ="documentos/" . $tip_id . "/" . $pro_id .'/'.$path;

    if ($this->storage::exists($existPath)) {
      $arrPath = explode('.', $path);
      $numDocument = explode('__', $arrPath[0]);
      if (count($numDocument)>1) {
        $latestNum  = end($numDocument);
        $latestNum++;
        $newName = $numDocument[0] ."__". strval($latestNum) . "." . $arrPath[1];
        $newName =  $this->validateNameFile($newName,$tip_id,$pro_id);
      } else {
        $newName = $arrPath[0] . '__' . "." . $arrPath[1];
        $newName =  $this->validateNameFile($newName,$tip_id,$pro_id);

      }
      $path = strtolower($newName);
      return $path;
      
    } else {
      $path = strtolower($path);
      return $path;
    }
  }

  function saveFile($name, $file, $tip_idr, $pro_idr, $extension)
{
    $tip_idx = intval($tip_idr);
    $pro_idx = intval($pro_idr);

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
  function deleteDocument($id)
  {
    $path = $this->document::select('doc_path')->where('doc_id', $id)->first();
    if ($path) {
      $this->storage::delete($path->doc_path);
    }
  }
}
