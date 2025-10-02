<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; // ✅ bukan Authenticatable
use Illuminate\Support\Facades\Schema;

class JwtToken extends Model
{
    use HasFactory;

    protected $table = 'jwt_token_user';
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
}
