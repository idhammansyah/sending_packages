<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use App\Models\Barang;
use App\Models\User;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'tbl_transaction';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    // ✅ auto isi fillable sesuai kolom di DB
    protected $fillable = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->fillable = $this->getAllowedFields();
    }

    protected function getAllowedFields()
    {
        return Schema::getColumnListing($this->getTable());
    }

    public function user() {
    return $this->belongsTo(User::class, 'id');
}

    public function barang() {
        return $this->belongsTo(Barang::class, 'id');
    }

    public function barangs()
    {
        $ids = explode(',', $this->id_barang ?? '');
        return \App\Models\Barang::whereIn('id', $ids)->get();
    }

}
