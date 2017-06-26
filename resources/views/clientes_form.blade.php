@extends('principal')

@section('conteudo')

<script src="{{url('/js/busca_cep.js')}}"></script>

<div class='col-sm-11'>
@if ($acao == 1)
    <h2> Inclusão de Clientes </h2>
@else
    <h2> Alteração de Clientes </h2>
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
</div>

@if ($acao == 1)
    <form method="post" action="{{route('carros.store')}}">
@else
    <form method="post" action="{{route('carros.update', $reg->id)}}">
    {!! method_field('put') !!}
@endif
        {{ csrf_field() }}
        
    <div class="col-sm-12">    
        <div class="form-group">
            <label for="nome">Nome do Cliente:</label>
            <input type="text" class="form-control" 
                   id="nome" name="nome" 
                   required>
        </div>
    </div>    

    <div class="col-sm-3">    
        <div class="form-group">
            <label for="cep">CEP:</label>
            <input type="text" class="form-control" 
                   id="cep" name="cep" 
                   required>
        </div>
    </div>    

    <div class="col-sm-8">    
        <div class="form-group">
            <label for="endereco">Endereço:</label>
            <input type="text" class="form-control" 
                   id="endereco" name="endereco" 
                   required>
        </div>
    </div>    
        
    <div class="col-sm-1">    
        <div class="form-group">
            <label for="num">Nº:</label>
            <input type="text" class="form-control" 
                   id="num" name="num" 
                   required>
        </div>
    </div>    

    <div class="col-sm-5">    
        <div class="form-group">
            <label for="bairro">Bairro:</label>
            <input type="text" class="form-control" 
                   id="bairro" name="bairro" 
                   required>
        </div>
    </div>    

    <div class="col-sm-6">    
        <div class="form-group">
            <label for="cidade">Cidade:</label>
            <input type="text" class="form-control" 
                   id="cidade" name="cidade" 
                   required>
        </div>
    </div>    

    <div class="col-sm-1">    
        <div class="form-group">
            <label for="estado">Estado:</label>
            <input type="text" class="form-control" 
                   id="estado" name="estado" 
                   required>
        </div>
    </div>    
        
    <div class="col-sm-12">    
        <button type="submit" class="btn btn-primary">Enviar</button>        
        <button type="reset" class="btn btn-warning">Limpar</button>        
    </div>    
        
</form>    

@endsection