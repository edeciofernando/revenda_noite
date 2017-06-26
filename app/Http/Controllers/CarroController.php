<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Carro;
use App\Marca;
use Mail;
use App\Mail\AvisoPromocao;

class CarroController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        // verifica se (não) está autenticado
        if (!Auth::check()) {
            return redirect('/');
        }
//        $carros = Carro::all();
        $carros = Carro::paginate(3);
        return view('carros_list', compact('carros'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        // verifica se (não) está autenticado
        if (!Auth::check()) {
            return redirect('/');
        }
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
        // verifica se (não) está autenticado
        if (!Auth::check()) {
            return redirect('/');
        }
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
            'modelo' => ['required', 'unique:carros,modelo,' . $id, 'min:2', 'max:60'],
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
        // verifica se (não) está autenticado
        if (!Auth::check()) {
            return redirect('/');
        }

        // posiciona no registro a ser alterado e obtém seus dados
        $reg = Carro::find($id);

        $marcas = Marca::orderBy('nome')->get();

        return view('carros_foto', compact('reg', 'marcas'));
    }

    public function storeFoto(Request $request) {
        // obtém os dados do form
        $dados = $request->all();

        if (isset($dados['foto'])) {
            // obtém o id para identificar a foto
            $id = $dados['id'];
            $fotoId = $id . '.jpg';
            $request->foto->move(public_path('fotos'), $fotoId);
        }

        return redirect()->route('carros.index')
                        ->with('status', $request->modelo . ' com Foto Cadastrada!');
    }

    public function pesq() {
        // verifica se (não) está autenticado
        if (!Auth::check()) {
            return redirect('/');
        }
//        $carros = Carro::all();
        $carros = Carro::paginate(3);
        return view('carros_pesq', compact('carros'));
    }

    public function filtros(Request $request) {
        $modelo = $request->modelo;
        $precomax = $request->precomax;

        $filtro = array();

        if (!empty($modelo)) {
            array_push($filtro, array('modelo', 'like', '%' . $modelo . '%'));
        }

        if (!empty($precomax)) {
            array_push($filtro, array('preco', '<=', $precomax));
        }

        $carros = Carro::where($filtro)
                ->orderBy('modelo')
                ->paginate(3);

        return view('carros_pesq', compact('carros'));
    }

    public function filtros2(Request $request) {
        $modelo = $request->modelo;
        $precomax = $request->precomax;

        $carros = Carro::where('modelo', 'like', '%' . $modelo . '%')
                ->where('preco', '<=', $precomax)
                ->orderBy('modelo')
                ->paginate(3);

        return view('carros_pesq', compact('carros'));
    }

    public function graf() {
        $carros = DB::table('carros')
                ->join('marcas', 'carros.marca_id', '=', 'marcas.id')
                ->select('marcas.nome as marca', DB::raw('count(*) as num'))
                ->groupBy('marcas.nome')
                ->get();

        return view("carros_graf", compact("carros"));
    }

    public function enviaMail() {
        $destinatario = "edeciofernando@gmail.com";
        Mail::to($destinatario)->subject("Promoção de Aniversário")
                ->send(new AvisoPromocao());
    }

    // devolve dados em JSON do id passado como parâmetro
    public function ws($id = null) {
        // indica o tipo de retorno do método
        header("Content-type: application/json; charset=utf-8");

        // caso o id não tenha sido informado
        if ($id == null) {
            $retorno = array(
                "status" => "url incorreta",
                "modelo" => null,
                "marca" => null,
                "ano" => null,
                "preco" => null);
        } else {
            // pesquisa pelo id informado
            $reg = Carro::find($id);

            // se encontrou registro
            if (isset($reg)) {
                $retorno = array(
                    "status" => "encontrado",
                    "modelo" => $reg->modelo,
                    "marca" => $reg->marca->nome,
                    "ano" => $reg->ano,
                    "preco" => $reg->preco);
            } else {
                $retorno = array(
                    "status" => "Carro Inexistente",
                    "modelo" => null,
                    "marca" => null,
                    "ano" => null,
                    "preco" => null);
            }
        }
        // converte array para o formato JSON
        echo json_encode($retorno, JSON_PRETTY_PRINT);
    }

    // exemplo devolvendo XML
    public function xml($id = null) {
        // indica o tipo de retorno da função
        header("Content-type: application/xml");

        // inicializa a biblioteca SimpleXML
        $xml = new \SimpleXMLElement(''
                . '<?xml version="1.0" encoding="utf-8"?>'
                . '<carros></carros>');

        // se id não foi passado
        if ($id == null) {
            $item = $xml->addChild("carro");
            $item->addChild("status", "url incorreta");
            $item->addChild("modelo", null);
            $item->addChild("marca", null);
            $item->addChild("ano", null);
            $item->addChild("preco", null);
        } else {
            // pesquisa o id
            $reg = Carro::find($id);

            // se encontrou
            if (isset($reg)) {
                $item = $xml->addChild("carro");
                $item->addChild("status", "encontrado");
                $item->addChild("modelo", $reg->modelo);
                $item->addChild("marca", $reg->marca->nome);
                $item->addChild("ano", $reg->ano);
                $item->addChild("preco", $reg->preco);
            } else {
                $item = $xml->addChild("carro");
                $item->addChild("status", "Carro Inexistente");
                $item->addChild("modelo", null);
                $item->addChild("marca", null);
                $item->addChild("ano", null);
                $item->addChild("preco", null);
            }
        }
        // exibe a variável no formato XML
        echo $xml->asXML();
    }

    // exemplo devolvendo XML
    public function listaXML($id = null, $id2 = null) {
        // indica o tipo de retorno da função
        header("Content-type: application/xml");

        // inicializa a biblioteca SimpleXML
        $xml = new \SimpleXMLElement(''
                . '<?xml version="1.0" encoding="utf-8"?>'
                . '<carros></carros>');

        // se id não foi passado
        if ($id == null || $id2 == null) {
            $item = $xml->addChild("carro");
            $item->addChild("status", "url incorreta");
            $item->addChild("modelo", null);
            $item->addChild("marca", null);
            $item->addChild("ano", null);
            $item->addChild("preco", null);
        } else {
            // pesquisa o id
            $reg = Carro::where('id', '>=', $id)
                    ->where('id', '<=', $id2)
                    ->get();

            // se nº registros > 0
            if (count($reg) > 0) {
                foreach ($reg as $c) {
                    $item = $xml->addChild("carro");
                    $item->addChild("status", "encontrado");
                    $item->addChild("modelo", $c->modelo);
                    $item->addChild("marca", $c->marca->nome);
                    $item->addChild("ano", $c->ano);
                    $item->addChild("preco", $c->preco);
                }
            } else {
                $item = $xml->addChild("carro");
                $item->addChild("status", "Carro Inexistente");
                $item->addChild("modelo", null);
                $item->addChild("marca", null);
                $item->addChild("ano", null);
                $item->addChild("preco", null);
            }
        }
        // exibe a variável no formato XML
        echo $xml->asXML();
    }

}
