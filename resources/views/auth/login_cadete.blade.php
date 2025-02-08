@extends('layouts.app')

@section('inc_head')
    <link rel="stylesheet" href="{{ asset('Styles/access.css') }}">
@endsection

@section('output')
<div class="extern">
    <x-header1></x-header1>
  
  
    <h2>Bienvenido</h2>
    <div id="expandible">
      <div id="cambio_acc"><a class="underH" href="{{ route('login') }}">>Acc. Cadete</a></div>
    </div>
  
    <div class="formu row">
      <form action="" method="post" class="container-reg col-lg-12">
        @csrf
        <h1>Login Cadete</h1>
        
        <label for="email"><b>email</b></label>
        <input type="text" placeholder="Introduzca el email" name="email" id="email" required>

        <label for="password"><b>Contraseña</b></label>
        <input type="password" placeholder="Introduzca su contraseña" name="password" id="password" required>

        <button type="submit" class="btn">Ingresar</button>
      </form>

      <!-- Error Alert -->
                                          
      @if($errors->any())
          <div class="alert alert-danger alert-dismissible fade show col-lg-12">
              <strong>Error!</strong> {{$errors->first()}}
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
      @endif
        
    </div>
</div>
@endsection

@section('inc_script_end', '{{-- <!*******************************> --}}')