
<?php
use App\Http\Controllers\ShortLinkController;

$router->get('/{short_link}', 'ShortLinkController@redirect');
