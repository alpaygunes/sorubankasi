<?php
Route::post('/mobil/oturumAcikmi', 'Mobile\MobilUyelikCtrl@oturumAcikmi');
Route::post('/mobil/dogrula', 'Mobile\MobilUyelikCtrl@dogrula')->middleware(['IsGuest']);
Route::post('/mobil/hesapOlustur', 'Mobile\MobilUyelikCtrl@hesapOlustur')->middleware(['IsGuest']);
Route::get('/mobil/logout', 'Mobile\MobilUyelikCtrl@logout')->middleware(['IsMember']);


Route::get('/mobil/profil', 'Mobile\MobilProfilCtrl@profil')->middleware(['IsMember']);

