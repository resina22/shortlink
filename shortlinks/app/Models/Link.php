<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Link extends Model {
    protected $table = 'links';
    protected $fillable = ['target', 'short'];

    public function create(string $target, string $short = null) {
        if(!$this->identifyProtocol($target)) {
            return false;
        }

        $this->target = $target;
        $this->short = is_null($short) ? $this->generateShort() : $this->customShort($short);
        $this->short_link = $this->protocol.'://'.env('APP_DOMAIN').'/'.$this->short;

        if( $this::save() ) {
            return $this;
        }

        return false;
    }

    private function existsShort($short) {
        $valid = self::where('short', 'like', "$short%")->orderBy('id', 'desc')->limit(1);
        return $valid->exists() ? $valid->get()->pluck('id')->first() : false;
    }

    private function generateShort() {
        $valid = null;

        do {
            $short = substr(md5(uniqid(rand(), true)), 0, 6);
            $valid = $this->existsShort($short);
        } while(!empty($valid));

        return $short;
    }

    private function customShort(string $short) {
        $idShort = intval($this->existsShort($short));
        $uniqueShort = substr($this->generateShort(), 0, 3);

        if($idShort){
            $idShort++;
            return $short.'.'.$idShort.'-'.$uniqueShort;
        }
        return $short.'-'.$uniqueShort;
    }

    private function identifyProtocol(string $target) {
        $target = trim($target);
        $valid = preg_match_all('/(https?|ftp)/', $target);

        if(!$valid) {
            return false;
        }

        $explode = explode('://', $target);
        $this->protocol = array_shift($explode);

        return true;
    }
}
