<?php
Route::group(['prefix'=>'kundali', 'as'=>'kundali.'], function(){
  Route::get('/', 'KundaliController@kundali')->name('index');
  Route::post('/', 'KundaliController@kundaliEnquirySubmit')->name('kundalienquiry');
  Route::get('report', 'KundaliController@kundaliDetail')->name('report');
  Route::post('gem-suggestion', 'KundaliController@gemSuggestions')->name('gem-suggestion');
  Route::post('rudraksh-suggestion', 'KundaliController@rudrakshSuggestion')->name('rudraksh-suggestion');
  Route::post('ashtakvarga', 'KundaliController@kundaliAkvargaChart')->name('ashtakvarga');
  Route::post('chart', 'KundaliController@kundaliChart')->name('chart');
  Route::post('basic', 'KundaliController@basicDetails')->name('basic');
  Route::post('jaimini-karakas', 'KundaliController@kundaliKarakas')->name('jaimini-karakas');
  Route::post('transit-dates', 'KundaliController@transitDates')->name('transit-dates');
  Route::post('transit-chart', 'KundaliController@transitChart')->name('transit-chart');
  Route::group(['prefix'=>'dosha', 'as'=>'dosha.'], function(){
      Route::post('manglik', 'KundaliController@kundaliReportManglik')->name('manglik');
      Route::post('kalsarpa', 'KundaliController@kundaliReportKalsarpa')->name('kalsarpa');
      Route::post('sadesati', 'KundaliController@kundaliReportSadesati')->name('sadesati');
      Route::post('mangal', 'KundaliController@mangalDosh')->name('mangal');
  });
  Route::group(['prefix'=>'dasha', 'as'=>'dasha.'], function(){
    Route::post('current-mahadasha', 'KundaliController@currentMahadasha')->name('current-mahadasha');
    Route::post('antardasha', 'KundaliController@kundaliAntardasha')->name('antardasha');
    Route::post('mahadasha', 'KundaliController@kundaliMahadasha')->name('mahadasha');
    Route::post('yogini', 'KundaliController@kundaliDashaYogini')->name('yogini');
  });
});

Route::get('panchang', 'KundaliController@panchang')->name('panchang');
