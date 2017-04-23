<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Carro;

class CarroController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $carros = Carro::all();
        
        return view('carros_list', compact('carros'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // 1: indica inclusão
        $acao = 1;
        
        return view('carros_form', compact('acao'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
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
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // posiciona no registro a ser alterado e obtém seus dados
        $reg = Carro::find($id);
        
        $acao = 2;
        
        return view('carros_form', compact('reg', 'acao'));        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
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
    public function destroy($id)
    {
        //
    }
}
