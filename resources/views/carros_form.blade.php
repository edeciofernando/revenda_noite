@extends('principal')

@section('conteudo')

<script src="{{url('/js/jquery.mask.min.js')}}"></script>

<div class='col-sm-11'>
@if ($acao == 1)
    <h2> Inclusão de Carros </h2>
@else
    <h2> Alteração de Carros </h2>
@endif
</div>
<div class='col-sm-1'>
    <a href="{{route('carros.index')}}" class="btn btn-primary" 
       role="button">Voltar</a>
</div>

<div class='col-sm-12'>

@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif        
    
@if ($acao == 1)
    <form method="post" action="{{route('carros.store')}}">
@else
    <form method="post" action="{{route('carros.update', $reg->id)}}">
    {!! method_field('put') !!}
@endif
        {{ csrf_field() }}

        <div class="form-group">
            <label for="modelo">Modelo do Veículo:</label>
            <input type="text" class="form-control" id="modelo"
                   name="modelo" 
                   value="{{$reg->modelo or old('modelo')}}"
                   required>
        </div>

        <div class="form-group">
            <label for="marca_id">Marca:</label>
            <select class="form-control" id="marca_id" name="marca_id">
            @foreach($marcas as $m)    
            <option value="{{$m->id}}"
@if ((isset($reg) and $reg->marca_id == $m->id) or 
     old('marca_id') == $m->id) selected @endif>
                       {{$m->nome}}</option>
            @endforeach    
            </select>
        </div>
                
        <div class="form-group">
            <label for="cor">Cor:</label>
            <input type="text" class="form-control" id="cor"
                   name="cor" 
                   value="{{$reg->cor or old('cor')}}"
                   required>
        </div>

        <div class="form-group">
            <label for="ano">Ano:</label>
            <input type="text" class="form-control" id="ano"
                   name="ano" 
                   value="{{$reg->ano or old('ano')}}"
                   required>
        </div>

        <div class="form-group">
            <label for="preco">Preço R$:</label>
            <input type="text" class="form-control" id="preco"
                   name="preco" 
                   value="{{$reg->preco or old('preco')}}"
                   required>
        </div>

        <div class="form-group">
            <label for="combustivel">Combustível:</label>
            <select class="form-control" id="combustivel" name="combustivel">
                <option value="A"
@if ((isset($reg) and $reg->combustivel == "Álcool") or 
     old('combustivel') == "A") selected @endif                        
                        >Álcool</option>
                <option value="G"
@if ((isset($reg) and $reg->combustivel == "Gasolina") or old('combustivel') == "G") selected @endif                                                
                        >Gasolina</option>
                <option value="D"
@if ((isset($reg) and $reg->combustivel == "Diesel") or old('combustivel') == "D") selected @endif                        
                        >Diesel</option>
                <option value="F"
@if ((isset($reg) and $reg->combustivel == "Flex") or old('combustivel') == "F") selected @endif                                                
                        >Flex</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Enviar</button>        
        <button type="reset" class="btn btn-warning">Limpar</button>        
    </form>    
</div>    

<script>
$(document).ready(function () {
    $('#preco').mask("#.###.##0,00", {reverse: true});
});
</script>    

@endsection