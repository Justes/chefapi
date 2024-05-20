<?php
/**
 * BaseModel.php
 *
 * @copyright  Aoya Inc.
 * @author     Lin <lin@aoya.it>
 * @created    2024/5/20 10:31
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Base extends Model
{
    protected $hidden = ['deleted_at'];

    public function trim($data)
    {
        $columnList = Schema::getColumnListing(app()->make($this->model)->getTable());
        foreach ($data as $key => $item) {
            if (!in_array($key, $columnList)) unset($data[$key]);
        }
        return $data;
    }

    protected function serializeDate(\DateTimeInterface $date): string
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('Y-m-d H:i:s');
    }

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }
}
