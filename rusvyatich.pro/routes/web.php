<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//Route::post('/successPay', 'PaymentController@success'); роуты для оплаты через систему оплат
//Route::post('/errorPay', 'PaymentController@error');
//Route::post('/pendingPay', 'PaymentController@pending');
//Route::post('/resultPay', 'PaymentController@result');
Route::get('/sql','MySqlQuerryController@sqlQuerry');
Route::post('/closeWindow', 'CloseWindowController@close');
Route::post('/passwordAdd','PasswordAddController@addPassword');
Route::get('/reset/{id_user}/{access_hash}','NewPasswordController@index');
Route::post('/newPassword','NewPasswordController@newPassword');
Route::group(['middleware' => 'AuthMine'], function () {
	Route::get('/','IndexController@index'); //Главная страница сайта
	Route::post('/getNewChanges','IndexController@getNewChanges'); //получение колва не прочитанных заказов
	Route::get('/home')->name('home')->middleware('Entitle');
	Route::get('/shop', 'ShopController@index')->name('shop');
	Route::get('/shop/knife{id}', 'KnifeController@index');
	Route::get('/shop/serialKnife{id}', 'KnifeController@serial');

	/*Страницы оплатить сейчас*/ 
	/*Route::get('/payNow/{id}', 'PayNowController@index'); активно при подключении к системе оплат
	Route::get('/payNowAuth/{id}', 'PayNowController@authPay');
	Route::post('/refuseOrderUnauth', 'PayNowController@refuseOrderUnauth');*/

	/* Post запросы для главной страницы*/
	Route::post('/getPath','ConstructorAppController@getPath');
	Route::post('/sortConstruct', 'ConstructorAppController@sortConstruct');
	Route::post('/getTexture','ConstructorAppController@getTexture');
	Route::post('/getDescription','ConstructorAppController@getDescription');

	Route::post('/getDescriptionSend','TypeSendController@getDescription');

	Route::post('/addToCart','CartController@addToCart');
	Route::post('/addToCartSerial','CartController@addToCartSerial');
	Route::post('/searchInCart','CartController@searchInCart');
	Route::post('/checkCart','CartController@checkCart');
	Route::post('/getKnifesForCart','CartController@getKnifesForCart');
	Route::post('/removeFromCart','CartController@removeFromCart');
	Route::post('/removeFromCartSerial','CartController@removeFromCartSerial');
	Route::post('/cleanCart','CartController@cleanCart');

	/*Routes доступные только заказчикам (совершения заказов)*/
	Route::group(['middleware' => 'Purchase'], function () {
		Route::post('/sendCart','OrderCartController@sendCart');
		Route::post('/sendConstruct','OrderConstructController@sendConstruct');
		//Route::post('/sendConsultConstruct','OrderConstructController@sendConsultConstruct');
		Route::post('/sendDrawing','DrawingOrderController@sendDrawing');
	});

	Route::post('/authUser','AutorizeController@authUser');
	Route::get('/auth','AutorizeController@index')->name('authorize');
	Route::get('/conditions','ConditionsController@index');
	Route::get('/album','AlbumController@index');
	Route::get('/coldArms','ColdSteelController@index');

	// Route::post('/resetPassword','ResetPasswordController@resetPassword'); восстановление пароля по смс
	// Route::get('/auth/resetPassword','ResetPasswordController@index')->name('reset');
	Route::post('/resetPasswordByEmail','ResetPasswordController@resetPasswordByEmail'); 
	Route::get('/auth/resetPwd','ResetPasswordController@email')->name('resetEmail');
	Route::post('/getKnifeForCustomer/{id}', 'KnifeController@getKnife');
	Route::post('/getKnifesByParameters', 'KnifeController@getKnifesByParameters');
	Route::get('/home/user', 'UserController@index');
	Route::get('/home/user/changePassword', 'ChangePasswordController@index');
	Route::post('/changePassword', 'ChangePasswordController@changePassword');
	Route::post('/changeUser', 'UserController@changeUser');
	Route::post('/dropUser', 'UserController@dropUser');
	Route::post('/dropUserByOperator', 'UserController@dropUserByOperator');
	Route::post('/outUser','AutorizeController@outUser');

		Route::group(['middleware' => 'Purchase'], function () {
			Route::post('/sendCartAuth','OrderCartController@sendCartAuth');
			Route::post('/sendConstructAuth','OrderConstructController@sendConstructAuth');
		});

		Route::group(['middleware' => 'AdminOperatorMainMasterPost'], function () {
			Route::post('/getOrders','HomeController@getOrders');
			Route::post('/savePurpose', 'ChangeOrderController@savePurpose');
		});
		Route::post('/getOrdersCustomer','HomeController@getOrdersCustomer')->middleware('MasterAndMainCustomerPost');

		Route::group(['middleware' => 'ViewListOrders'], function () {
			Route::get('/home/constructOrders', 'HomeController@index')->name('adminHome'); // Страница заказов(домашняя)
			Route::get('/home/individualOrders','IndividualOrdersController@index')->name('adminIndividual');
			Route::get('/home/allOrders','AllOrdersController@index')->name('adminAll');
			Route::get('/home/cartOrders', 'CartOrdersController@index')->name('adminCart');
			Route::get('/home/orders', 'HomeController@index')->name('customerHome'); // Страница заказов(домашняя) для мастера и заказчика
		});

		Route::get('/downloadSvg/{bladePath}/{bolsterPath}/{handlePath}/{bolsterWidth}/{handle}/{blade}/{handleMaterial}/{bolster}/{steel}/{bladeLength}/{bladeHeight}/{buttWidth}/{handleLength}/{bladeTransform}/{bolsterTransform}/{handleTransform}/{bladeColor}/{bolsterColor}/{handleColor}/{bolsterWrapTransform}/{bladeWrapTransform}/{handleWrapTransform}/{fixBladeTransform}/{orderId}', 'DownloadSvgController@download')->middleware('ChangeStatusOrderAccess');  // для работников;

		/*Routes для страницы заказов из корзины/конструктора для работников*/
		Route::group(['middleware' => 'ViewOrderCartConstruct'], function () {
			Route::get('/home/constructOrders/{id}', 'PageOrderConstructController@index');
			
			Route::get('/home/cartOrders/{id}', 'PageOrderCartController@index');
		});
		Route::get('/home/individualOrders/{id}', 'PageOrderIndividualController@index')->middleware('ViewOrderIndividual');

		Route::group(['middleware' => 'AdminAndMainMasterPost'], function () { 
			Route::post('/updateKnife/{id}', 'KnifeEditController@updateKnife');
			Route::post('/dropKnife/{id}', 'KnifeEditController@dropKnife');
			Route::post('/saveKnife', 'KnifeAddController@addKnife');

			Route::post('/updateSerialKnife/{id}', 'KnifeEditController@updateSerialKnife');
			Route::post('/dropSerialKnife/{id}', 'KnifeEditController@dropSerialKnife');
			Route::post('/saveSerialKnife', 'KnifeAddController@addSerialKnife');
		});
		Route::group(['middleware' => 'AdminMainOperatorMainMasterPost'], function () {
			Route::post('/saveWorker', 'AddWorkerController@add');
		});
		Route::get('/home/knife/add', 'KnifeAddController@index')->middleware('AdminAndMainMaster');
		Route::get('/home/knifeSerial/add', 'KnifeAddController@serial')->middleware('AdminAndMainMaster');
		Route::get('/home/changeConstruct', function(){
			return redirect('/home/changeConstruct/steels');
		});

		/*Routes для отправлений получение сообщений*/
		Route::group(['middleware' => 'ForAuthorize'], function () { 
			Route::post('/sendMessage', 'MessageController@sendMessage');
			Route::post('/getMessages', 'MessageController@getMessages');
			Route::post('/outOfMessage', 'MessageController@outOfMessage');
		});

		Route::post('/refuseOrder', 'ChangeOrderController@refuseOrder'); //защита в контроллере!
		Route::group(['middleware' => 'ChangeStatusOrderAccess'], function () { 
			Route::post('/changeStatus', 'ChangeOrderController@changeStatus'); // для работников
		});
		Route::post('/changeMaster', 'ChangeOrderController@changeMaster')->middleware('ChangeMasterAccess'); //для Главного мастера


		Route::group(['middleware' => 'ChangeSumOrderAccess'], function () { 
			Route::post('/changeSum', 'ChangeOrderController@changeSum');
			Route::post('/changeDay', 'ChangeOrderController@changeDay');
			Route::post('/changePay', 'ChangeOrderController@changePay');
			Route::post('/changeSend', 'ChangeOrderController@changeSendType');
		});

		/*Routes для просмотра свойств конструктора все кроме мастера и заказчика*/
		Route::group(['middleware' => 'KnifeProperties'], function () { 
			Route::get('/home/changeConstruct/handleMaterials', 'ChangeHandleMaterialController@index');
			Route::get('/home/changeConstruct/handleMaterials/{id}', 'UpdateHandleMaterialController@index');
			Route::get('/home/changeConstruct/steels', 'ChangeSteelController@index');
			Route::get('/home/changeConstruct/steels/{id}', 'UpdateSteelController@index');
			Route::get('/home/changeConstruct/blades', 'ChangeBladeController@index');
			Route::get('/home/changeConstruct/blades/{id}', 'UpdateBladeController@index');
			Route::get('/home/changeConstruct/bolsters', 'ChangeBolsterController@index');
			Route::get('/home/changeConstruct/bolsters/{id}', 'UpdateBolsterController@index');
			Route::get('/home/changeConstruct/handles', 'ChangeHandleController@index');
			Route::get('/home/changeConstruct/handles/{id}', 'UpdateHandleController@index');
			Route::get('/home/changeConstruct/sizes', 'ChangeSizesController@index');
			Route::get('/home/changeConstruct/addSize', 'AddSizeController@index');
		});

		/*Routes для добавления свойств конструктора только Админ*/
		Route::group(['middleware' => 'OnlyAdmin'], function () { 
			Route::get('/home/changeConstruct/addSteel', 'AddSteelController@index');
			Route::get('/home/changeConstruct/addBolster', 'AddBolsterController@index');
			Route::get('/home/changeConstruct/addBlade', 'AddBladeController@index');
			Route::get('/home/changeConstruct/addHandle', 'AddHandleController@index');
			Route::get('/home/changeConstruct/addHandleMaterial', 'AddHandleMaterialController@index');
			Route::get('/home/workers/mainMasters', 'MainMastersController@index')->name('mainMasters');
			Route::get('/home/workers/mainMasters/{id}', 'AboutMainMasterController@index');
			Route::get('/home/workers/mainOperators', 'MainOperatorsController@index')->name('mainOperators');///?????
			Route::get('/home/statistic', 'StatisticController@index');
		});

		/*Routes для действий со свойствами конструктора только Админ*/
		Route::group(['middleware' => 'OnlyAdminPost'], function () { 
			Route::post('/addSteel', 'AddSteelController@addSteel');
			Route::post('/updateSteel/{id}/{action}', 'UpdateSteelController@updateSteel');
			Route::post('/addBolster', 'AddBolsterController@addBolster');
			Route::post('/updateBolster/{id}/{action}', 'UpdateBolsterController@updateBolster');
			Route::post('/addBlade', 'AddBladeController@addBlade');
			Route::post('/updateBlade/{id}/{action}', 'UpdateBladeController@updateBlade');
			Route::post('/addHandle', 'AddHandleController@addHandle');
			Route::post('/updateHandle/{id}/{action}', 'UpdateHandleController@updateHandle');
			Route::post('/addHandleMaterial', 'AddHandleMaterialController@addHandleMaterial');
			Route::post('/updateHandleMaterial/{id}/{action}', 'UpdateHandleMaterialController@updateHandleMaterial');
		});


		Route::get('/home/workers')->middleware('WorkersRedirect');
		Route::get('/home/workers/masters', 'MastersController@index')->name('workersMasterHome')->middleware('WorkersPageAccess');
		Route::get('/home/workers/operators', 'WorkersController@index')->name('workersAdminHome')->middleware('WorkersPageAccess');

		/*Routes для просмотра работников админом и главным мастером*/
		Route::group(['middleware' => 'AdminAndMainMaster'], function () {
			Route::get('/home/workers/masters/{id}', 'AboutMasterController@index');
			Route::get('/home/workers/add', 'AddWorkerController@index')->name('addWorker');
		});
		/*Routes для просмотра работников админом и главным оператором*/
		Route::group(['middleware' => 'AdminAndMainOperator'], function () {
			Route::get('/home/workers/operators/{id}', 'AboutOperatorController@index');
		});

		Route::get('/home/knifes' , function(){
			return redirect()->route('knifesSerialPage');
		});
		
		Route::group(['middleware' => 'AdminOperatorMainMaster'], function () {
			Route::get('/home/user{id}', 'UserOrderController@index');
			Route::get('/home/knifes/individual', 'EditKnifesController@index')->name('knifesPage');
			Route::get('/home/knifes/serial', 'EditKnifesController@serial')->name('knifesSerialPage');
			Route::get('/home/knifes/individual/{id}', 'KnifeEditController@index');
			Route::get('/home/knifes/serial/{id}', 'KnifeEditController@serial');
		});

		/*Routes для страниц просмотра заказов номера для заказчиков*/
		Route::group(['middleware' => 'OnlyCustomer'], function () { 
			Route::get('/home/ordersConstruct/{id}', 'PageUserConstructController@index');
			Route::get('/home/ordersIndividual/{id}', 'PageUserIndividualController@index');
			Route::get('/home/ordersCart/{id}', 'PageUserCartController@index');
			/*Route::get('/home/{orderType}/{id}/pay', 'PayInSystemController@index'); роуты активны при подключенной системе оплат
			Route::get('/home/{orderType}/{id}/pay', 'PayInSystemController@index');
			Route::get('/home/{orderType}/{id}/pay', 'PayInSystemController@index');*/
		});
});
