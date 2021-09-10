<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\UsesUuid;
use App\Traits\ApiResponses;
use Illuminate\Support\Facades\Storage;
use App\Models\File;
use App\Http\Requests\FilePostRequest;

class FileController extends Controller
{	
	use ApiResponses;
    //
	
	public function store(FilePostRequest $request){
		
		$validated = $request->validated();
		
		$path = isset($request->path) ? $request->path : 'upload'; // default		
		
		$aws_path = $request->file('file')->store($path, 's3');

        Storage::disk('s3')->setVisibility($path, 'private');

        $file = File::create([
            'filename' => basename($request->file('file')->getClientOriginalName()),
            'url' => $aws_path,
			'size' => $request->file('file')->getSize()
        ]);
				
		return $this->jsonResponse([
			'id' => $file->id,
			'name' => $file->filename,
			'link' => '//storage-nginx/storage/file/' . $file->id,
			'size' => $file->size],
		201);
	}
	
	public function get_file($id)
	{	
		if ($file = File::find($id)) {
			// Реализация кеша и 304 ответа
			$LastModified_unix = strtotime(date("D, d M Y H:i:s", $file->created_at->getTimestamp())); 
			$LastModified = gmdate("D, d M Y H:i:s \G\M\T", $LastModified_unix); 
			$IfModifiedSince = false;
			if (isset($_ENV['HTTP_IF_MODIFIED_SINCE'])) $IfModifiedSince = strtotime(substr($_ENV['HTTP_IF_MODIFIED_SINCE'], 5));  
			if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) $IfModifiedSince = strtotime(substr($_SERVER['HTTP_IF_MODIFIED_SINCE'], 5));
			if ($IfModifiedSince && $IfModifiedSince >= $LastModified_unix) {
				header($_SERVER['SERVER_PROTOCOL'] . ' 304 Not Modified');
				exit;
			}
			header('Last-Modified: '. $LastModified);
			return Storage::disk('s3')->response($file->url);
		}
				
		return $this->jsonResponse([
			'status' => 404,
			'title' => 'Not Found',
			'detail' => 'Not Found'],			
		404);
	}
	
	public function get_info($id)
	{
		$allowed_field = [
			'id' => 'id',
			'name' => 'filename',
			'link' => 'url',
			'size' => 'size'			
		];
		
		if (request()->has('field')) {
			$fields = explode(',',request()->field);
			$output_fields = array_intersect($fields,array_keys($allowed_field));
			// Если заданы левые поля и на выходе ни одного пересекающегося - то весь список отдаем
			if (empty($output_fields)) {
				$output_fields = array_keys($allowed_field);
			}			
		} else {
			$output_fields = array_keys($allowed_field);
		}
		
		if ($file = File::find($id)) {			
			$data = [
				'id' => $file->id,
				'name' => $file->filename,
				'link' => '//storage-nginx/storage/file/' . $file->id,
				'size' => $file->size
			];
			
			foreach (array_diff(array_keys($allowed_field),$output_fields) AS $item) {				
				unset($data[$item]);
			}
			
			return $this->jsonResponse($data,201);
		}

				
		return $this->jsonResponse([
			'status' => 404,
			'title' => 'Not Found',
			'detail' => 'Not Found'],			
		404);
	}
	
	public function destroy($id)
	{
		if ($file = File::find($id)) {

			Storage::disk('s3')->delete($file->url);			
			
			$file->delete();
						
			return $this->jsonResponse(['ok'],204);
		}

				
		return $this->jsonResponse([
			'status' => 404,
			'title' => 'Not Found',
			'detail' => 'Not Found'],			
		404);
		
	}
}
