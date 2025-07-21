<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfessorStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'nome' => [
                'required',
                'string',
                'min:3',
                'max:255',
                'regex:/^[a-zA-Z\s]+$/u'
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:professores,email'
            ],
            'cpf' => [
                'required',
                'string',
                'unique:professores,cpf',
                'regex:/^\d{3}\.\d{3}\.\d{3}-\d{2}$/'
            ],
            'telefone' => [
                'required',
                'string',
                'regex:/^\d{11}$/'
            ],
            'especialidade' => [
                'required',
                'string',
                'max:255'
            ],
            // Removido o campo 'formacao' das regras de validação
            'observacoes' => [
                'nullable',
                'string',
                'max:1000'
            ],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nome.required' => 'O campo Nome Completo é obrigatório.',
            'nome.string' => 'O campo Nome Completo deve ser um texto.',
            'nome.min' => 'O Nome Completo deve ter no mínimo :min caracteres.',
            'nome.max' => 'O Nome Completo deve ter no máximo :max caracteres.',
            'nome.regex' => 'O Nome Completo deve conter apenas letras e espaços.',

            'email.required' => 'O campo E-mail é obrigatório.',
            'email.email' => 'O E-mail deve ser um endereço de e-mail válido.',
            'email.max' => 'O E-mail deve ter no máximo :max caracteres.',
            'email.unique' => 'Este e-mail já está cadastrado para outro professor.',

            'cpf.required' => 'O campo CPF é obrigatório.',
            'cpf.unique' => 'Este CPF já está cadastrado para outro professor.',
            'cpf.regex' => 'O CPF deve estar no formato 000.000.000-00.',

            'telefone.required' => 'O campo Telefone é obrigatório.',
            'telefone.regex' => 'O Telefone deve conter exatamente 11 dígitos numéricos (DDD + número).',

            'especialidade.required' => 'O campo Especialidade é obrigatório.',
            'especialidade.string' => 'O campo Especialidade deve ser um texto.',
            'especialidade.max' => 'A Especialidade deve ter no máximo :max caracteres.',

            // Removidas as mensagens para o campo 'formacao'

            'observacoes.string' => 'O campo Observações deve ser um texto.',
            'observacoes.max' => 'As Observações devem ter no máximo :max caracteres.',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        // Remover o campo 'formacao' dos dados antes da validação
        if ($this->has('formacao')) {
            $this->request->remove('formacao');
        }
    }
}
