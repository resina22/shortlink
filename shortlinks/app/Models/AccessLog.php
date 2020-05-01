<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccessLog extends Model {
    protected $table = 'access_log';

    public function create(string $target, string $short = null) {
        if(!$this->identifyProtocol($target)) {
            return false;
        }

        $this->target = $target;
        $this->short  = is_null($short) ? $this->generateShort() : $this->customShort($short);
        $this->short_link = $this->protocol.'://'.env('APP_DOMAIN').'/'.$this->short;

        if( $this::save() ) {
            return $this;
        }

        return false;
    }


}
