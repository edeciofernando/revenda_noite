<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Carro;
use App\Marca;

class CarroController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $carros = Carro::all();

        return view('carros_list', compact('carros'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        // 1: indica inclusão
        $acao = 1;

        $marcas = Marca::orderBy('nome')->get();

        return view('carros_form', compact('acao', 'marcas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {

        $this->validate($request, [
            'modelo' => 'required|unique:carros|min:2|max:60',
            'ano' => 'required|numeric|min:1970|max:2020',
            'cor' => 'min:4|max:40'
        ]);

        // obtém os dados do form
        $dados = $request->all();

        $inc = Carro::create($dados);

        if ($inc) {
            return redirect()->route('carros.index')
                            ->with('status', $request->modelo . ' Incluído!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        // posiciona no registro a ser alterado e obtém seus dados
        $reg = Carro::find($id);

        $acao = 2;

        $marcas = Marca::orderBy('nome')->get();

        return view('carros_form', compact('reg', 'acao', 'marcas'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {

        $this->validate($request, [
            'modelo' => ['required', 'unique:carros,modelo,'.$id, 'min:2', 'max:60'],
            'ano' => 'required|numeric|min:1970|max:2020',
            'cor' => 'min:4|max:40'
        ]);

        // obtém os dados do form
        $dados = $request->all();

        // posiciona no registo a ser alterado
        $reg = Carro::find($id);

        // realiza a alteração
        $alt = $reg->update($dados);

        if ($alt) {
            return redirect()->route('carros.index')
                            ->with('status', $request->modelo . ' Alterado!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $car = Carro::find($id);
        if ($car->delete()) {
            return redirect()->route('carros.index')
                            ->with('status', $car->modelo . ' Excluído!');
        }
    }

    public function foto($id) {
        // posiciona no registro a ser alterado e obtém seus dados
        $reg = Carro::find($id);

        $marcas = Marca::orderBy('nome')->get();

        return view('carros_foto', compact('reg', 'marcas'));
    }
    
    public function storeFoto(Request $request) {
        // obtém os dados do form
        $dados = $request->all();
                
        if(isset($dados['foto'])) {
            // obtém o id para identificar a foto
            $id = $dados['id'];
            $fotoId = $id . '.jpg';
            $request->foto->move(public_path('fotos'), $fotoId);
        }

        return redirect()->route('carros.index')
               ->with('status', $request->modelo . ' com Foto Cadastrada!');
    }    
}
