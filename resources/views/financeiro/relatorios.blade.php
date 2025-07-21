
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Relatórios Financeiros</h4>
                </div>
                <div class="card-body">
                    <h5>Selecione um tipo de relatório:</h5>

                    <form action="{{ route('financeiro.relatorios.gerar') }}" method="POST" class="mt-4">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="tipo_relatorio">Tipo de Relatório</label>
                                    <select name="tipo_relatorio" id="tipo_relatorio" class="form-control">
                                        <option value="pagamentos">Pagamentos</option>
                                        <option value="mensalidades">Mensalidades</option>
                                        <option value="inadimplencia">Inadimplência</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="data_inicio">Data Início</label>
                                    <input type="date" name="data_inicio" id="data_inicio" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="data_fim">Data Fim</label>
                                    <input type="date" name="data_fim" id="data_fim" class="form-control">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Gerar Relatório</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
