<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\User;

class History extends Model
{
    protected $fillable = ['type', 'amount', 'total_before', 'total_after', 'user_id_transaction', 'date'];

    //relacionamento USUARIO -  HISTORICO
    public function user(){
        return $this->belongsTo(User::class);
    }

    //relacionamento: Pegando a informação de quem transferiu
    public function userSender(){
        return $this->belongsTo(User::class, 'user_id_transaction');
    }

    public function type($type = null){
        $types = [
            'I' => 'Entrada',
            'T' => 'Transferência',
            'O' => 'Saque'
        ];

        if (!$type) {
            return $types;
        }
        return $types[$type];

        //recuperando o tipo de entrada, tranferencia ou não.
        if ($this->user_id_transaction != null && $type == 'I'){
            return 'Recebido';
        }
    }

    public function scopeUserAuth($query){
        return  $query->where('user_id', auth()->user()->id);
    }

    public function getDateAttribute($value){
        //return Carbon::parse($value)->format('d/m/Y - H:m:s');
        return Carbon::parse($value)->format('d/m/Y');
    }

    /**
     * Get the relationships for the entity.
     *
     * @return array
     */
    public function getQueueableRelations()
    {
        // TODO: Implement getQueueableRelations() method.
    }

    public function pesquisa(Array $data, $paginacao){
        $historicos = $this->where( function ($query) use ($data){

            if (isset($data['id'])){
                $query->where('id', $data['id']);
            }
            if (isset($data['date'])){
                $query->where('date', $data['date']);
            }
            if (isset($data['type'])){
                $query->where('type', $data['type']);
            }
        })->userAuth()->with(['userSender'])->paginate($paginacao);
        //->toSql();
        //dd($historicos);

        return $historicos;
    }
}
