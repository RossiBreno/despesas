<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Storage;
use App\Models\despesa;

class DespesaController extends Controller
{
    public function createView(){
        return view('despesa.create');
    }

    public function create(Request $request){
        $request->validate([
            'value' => ['regex:/^.{2}\d{1,6}\,\d{2}$/', 'not_regex:/^.{2}0{1,}\,0{2}$/'],
            'date' => 'required|date_format:d/m/Y',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:8192',
            'desc' => 'required|string|max:500',
        ], [
            'value.regex' => 'Formato de Preço inválido!',
            'value.not_regex' => 'O Preço não pode ser R$0,00!',
            'date.*' => 'Formato de data inválido!',
            'image.required' => 'Você precisa enviar uma imagem!',
            'image.image' => 'O arquivo precisa ser uma imagem!',
            'image.mimes' => 'O arquivo precisa ser uma imagem!',
            'image.max' => 'A imagem só pode ter no máximo 8MB!',
            'desc.required' => 'Você precisa inserir uma descrição!',
            'desc.string' => 'Formato de descrição inválido!',
            'desc.max' => 'Não é permitido mais que 500 caracteres na descrição!',
        ]);

        $userId = Auth::id();

        $url = time().'.'.$request->image->extension();  
        $request->image->storeAs('despesas', $url);

        $despesa = new despesa();
        
        $newDate = explode('/', $request->date);
        $newDate = $newDate[2].'-'.$newDate[1].'-'.$newDate[0];

        $newValue = str_replace(',', '.', $request->value);
        $newValue = str_replace('R$', '', $newValue);
        $newValue = (float) $newValue;

        $despesa->user_id = $userId;
        $despesa->desc = $request->desc;
        $despesa->date = $newDate;
        $despesa->value = $newValue;
        $despesa->urlImage = $url;

        $despesa->save();

        return redirect()->route('dashboard.index');
    }

    public function imageView($despesaId) {
        $userId = Auth::id();
        $despesa = despesa::where('user_id', '=', $userId)->where('id', '=', $despesaId)->first();
        if($despesa == null) abort(403, 'Acesso não autorizado');

        return view('despesa.image', ['id' => $despesa->id]);
    }

    public function image($id) {
        $userId = Auth::id();
        $despesa = despesa::where('user_id', '=', $userId)->where('id', '=', $id)->first();

        if($despesa == null) abort(403, 'Acesso não autorizado');

        $path = storage_path('app/despesas/'. $despesa->urlImage);

        $file = File::get($path);
        $type = File::mimeType($path);

        $response = Response::make($file, 200);

        $response->header("Content-Type", $type);

        return $response;
    }

    public function editView($despesaId){
        $userId = Auth::id();
        $despesa = despesa::where('user_id', '=', $userId)->where('id', '=', $despesaId)->first();

        if($despesa == null) abort(403, 'Acesso não autorizado');

        $despesa->value = str_replace('.', ',', $despesa->value);
        $despesa->value = 'R$'.$despesa->value;

        $despesa->date = explode('-', $despesa->date);
        $despesa->date = $despesa->date[2].'/'.$despesa->date[1].'/'.$despesa->date[0];
        
        return view('despesa.edit', ['despesa' => $despesa]);
    }

    public function edit(Request $request, $despesaId){
        $request->validate([
            'value' => ['regex:/^.{2}\d{1,6}\,\d{2}$/', 'not_regex:/^.{2}0{1,}\,0{2}$/'],
            'date' => 'required|date_format:d/m/Y',
            'image' => 'image|mimes:jpeg,png,jpg|max:8192',
            'desc' => 'required|string|max:500',
        ], [
            'value.regex' => 'Formato de Preço inválido!',
            'value.not_regex' => 'O Preço não pode ser R$0,00!',
            'date.*' => 'Formato de data inválido!',
            'image.image' => 'O arquivo precisa ser uma imagem!',
            'image.mimes' => 'O arquivo precisa ser uma imagem!',
            'image.max' => 'A imagem so pode ter no maximo 8MB!',
            'desc.required' => 'Você precisa inserir uma descrição!',
            'desc.string' => 'Formato de descrição inválido!',
            'desc.max' => 'Não é permitido mais que 500 caracteres na descrição!',
        ]);
        
        $userId = Auth::id();
        $despesa = despesa::where('user_id', '=', $userId)->where('id', '=', $despesaId)->first();

        if($despesa == null) abort(403, 'Acesso não autorizado');

        if($request->image != null){
            $url = time().'.'.$request->image->extension();  
            $request->image->storeAs('despesas', $url);
            Storage::delete('despesas/'.$despesa->urlImage);
            $despesa->urlImage = $url;
        }

        $newDate = explode('/', $request->date);
        $newDate = $newDate[2].'-'.$newDate[1].'-'.$newDate[0];

        $newValue = str_replace(',', '.', $request->value);
        $newValue = str_replace('R$', '', $newValue);
        $newValue = (float) $newValue;

        $despesa->desc = $request->desc;
        $despesa->date = $newDate;
        $despesa->value = $newValue;


        $despesa->save();

        return redirect()->route('dashboard.index');
        
    }

    public function delete($despesaId){
        $userId = Auth::id();
        $despesa = despesa::where('user_id', '=', $userId)->where('id', '=', $despesaId)->first();

        if($despesa == null) abort(403, 'Acesso não autorizado');

        Storage::delete('despesas/'.$despesa->urlImage);
        $despesa->delete();

        return redirect()->route('dashboard.index');
    }
}
