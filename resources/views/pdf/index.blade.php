@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Módulo Financeiro') }}</div>

                <div class="card-body">
                    <div id="financeiro-app"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts do Preact -->
<script src="{{ asset('js/financeiro.js') }}" defer></script>
@endsection
