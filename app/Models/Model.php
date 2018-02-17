<?php
/**
 * Created by PhpStorm.
 * User: mabasasi
 * Date: 2018/02/16
 * Time: 2:37
 */

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Libraries\ModelTrait\RelationTrait;
use App\Libraries\ModelTrait\SearchQueryTrait;
use App\Libraries\ModelTrait\SelectBoxArrayTrait;

class Model extends \Illuminate\Database\Eloquent\Model {
    use RelationTrait;
    use SearchQueryTrait;
    use SelectBoxArrayTrait;
    use SoftDeletes;

    public function scopeUpdatedNewer($query) {
        return $query->orderBy($this->getUpdatedAtColumn(), 'DESC')
            ->orderBy('id', 'DESC');
    }

    public function scopeUpdatedOlder($query) {
        return $query->orderBy($this->getUpdatedAtColumn(), 'ASC')
            ->orderBy('id', 'ASC');
    }

    public function scopeCreatedNewer($query) {
        return $query->orderBy($this->getCreatedAtColumn(), 'DESC')
            ->orderBy('id', 'DESC');
    }

    public function scopeCreatedOlder($query) {
        return $query->orderBy($this->getCreatedAtColumn(), 'ASC')
            ->orderBy('id', 'ASC');
    }

}