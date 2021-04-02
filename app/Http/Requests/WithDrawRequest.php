<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Repositories\NabRepository;
use App\Repositories\UserRepository;
use Illuminate\Foundation\Http\FormRequest;

class WithDrawRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get current balance
     *
     * @return int
    */
    protected function getBalance()
    {
        $balance = 0;
        $userRepo = app(UserRepository::class);
        $user = $userRepo->find($this->user_id);
        if ($user instanceof User) {
            $NabRepo = app(NabRepository::class);
            $Nab = $NabRepo->getLastNab();
            $balance = $user->total_unit * $Nab->nab;
        }

        return $balance;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
    */
    public function rules()
    {
        $rules = [
            'user_id' => 'required|integer|exists:users,id',
            'amount_rupiah' => 'required|integer|max:0',
        ];
        $balance = $this->getBalance();
        if ($balance > 0) {
            $rules['amount_rupiah'] = str_replace('|max:0', '|min:0|max:' . $balance, $rules['amount_rupiah']);
        }
        return $rules;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
    */
    public function messages()
    {
        $messages = [
            'amount_rupiah.min' => 'The minimum withdraw is 1',
            'amount_rupiah.max' => 'The maximum withdraw is 0',
        ];
        $balance = $this->getBalance();
        if ($balance > 0) {
            $messages['amount_rupiah.max'] = str_replace('is 0', 'is ' . $balance, $messages['amount_rupiah.max']);
        }
        return $messages;
    }
}