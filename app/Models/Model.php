<?php
/**
 * Created by PhpStorm.
 * User: mabasasi
 * Date: 2018/02/16
 * Time: 2:37
 */

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Libraries\ModelTrait\RelationTrait;
use Libraries\ModelTrait\SearchQueryTrait;
use Libraries\ModelTrait\SelectBoxArrayTrait;

class Model extends \Illuminate\Database\Eloquent\Model {
    use RelationTrait;
    use SearchQueryTrait;
    use SelectBoxArrayTrait;
    use SoftDeletes;
}