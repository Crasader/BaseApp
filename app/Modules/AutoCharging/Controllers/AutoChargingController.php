<?php

namespace App\Modules\AutoCharging\Controllers;

use App\Modules\AutoCharging\Models\AutoChargingFees;
use Illuminate\Http\Request;
use App\Modules\Backend\Controllers\BackendController;
use App\Modules\Transaction\Controllers\TransactionController;
use Auth;
use DB;
use App\User;
use App\Modules\AutoCharging\Models\AutoCharging;
use App\Modules\AutoCharging\Models\AutoChargingsTelco;
use App\Modules\Group\Models\Group;
use App\Modules\Transaction\Models\Transaction;
use App\Modules\Wallet\Models\Wallet;
use App\Modules\AutoCharging\Models\AutoChargingSetting;
use App\Modules\AutoCharging\Models\AutoChargingProvider;

use App\Modules\AutoCharging\Providers\NapTheNgay\NapTheNgay;

class AutoChargingController extends BackendController
{
    public function index(Request $request)
    {
        $title    = "Tẩy thẻ qua api";
        $chargings = AutoCharging::orderBy('id','DESC')->paginate(40);

        if($request->input('control') == 'search')
        {
            $user    = $request->input('user');
            $telco   = $request->input('telco');
            $amount  = $request->input('amount');
            $title  = "Search: ";

            $chargings = new AutoCharging;
            if( $user != '' )
            {
                $chargings->where('user', $user);
            }
            $chargings->orderBy('id','DESC');
            $chargings->paginate(40);

            print_r($chargings);
            return exit();
            //$chargings = Charging::where('name', 'LIKE', '%'.$keyword.'%')->orderBy('id','DESC')->paginate(40);
        }
        return view("AutoCharging::index", compact('title','chargings'));
    }

    /*---------------- HISTORY ---------------*/
    public function history(Request $request)
    {
        $title    = "Card Charging History";
        $chargings = AutoCharging::orderBy('id','DESC')->paginate(40);
        if($request->input('keyword'))
        {
            $keyword = $request->input('keyword');
            $title  = "Search: ".$keyword;
            $chargings = AutoCharging::where('name', 'LIKE', '%'.$keyword.'%')->orderBy('id','DESC')->paginate(40);
        }
        return view("AutoCharging::history", compact('title','chargings'));
    }

    /*---------------- RESET ACTIONS ---------------*/
    /*
     *  0: pending
        1: success
        2: sai menh gia
        3: khongdungduonc
        4: dasudung
     */
    public function resetCharging($id)
    {
        $card = AutoCharging::findOrFail($id);
        if( $card->status == 0 )
        {
            return redirect()->route('autochargings.history')
                ->with('success','Charging reseted!');
        }
        if( $card->status == 3 || $card->status == 4 )
        {
            $card->status = 0;
            $card->update();
            return redirect()->route('autochargings.index')
                ->with('success','Charging Reset successfully');
        }

        $trans_code = $card->transaction_code;
        if(!$trans_code)
        {
            return redirect()->route('autochargings.index')
                ->withErrors(['message'=>'Mã giao dịch không tồn tại']);
        }

        DB::beginTransaction();
        try {
            $trans_id   = Transaction::where('transaction_code',$trans_code)->select('id')->first();
            $runTransaction = TransactionController::resetTransaction($trans_id->id);
            if( $runTransaction == 2 ) {
                $card->real_value = 0;
                $card->amount = $card->declared_value - ($card->declared_value * AutoChargingFees::getFees($card->telco)) / 100;
                $card->status = 0;
                $card->penalty = 0;
                $card->admin_note = '';
                $card->update();
                DB::commit();
            }
        }catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
        if( $runTransaction == 2 )
        {
            return redirect()->route('autochargings.index')
                ->with('success','Charging Reset successfully');
        }else{
            return redirect()->route('autochargings.index')
                ->withErrors(['message'=>'Charging reset not completed']);
        }
    }

    /*----------------- SETTING -------------*/
    public function settings()
    {
        $setting = ''; //ChargingSetting::get();
        $groups = Group::orderBy('id','DESC')->get();
        $telcos = AutoChargingsTelco::orderBy('id','DESC')->get();
        $fees = new AutoChargingFees;
        return view("AutoCharging::settings", compact('setting','groups','telcos', 'fees') );
    }


    public function createTelco()
    {
        return view("AutoCharging::create-telco" );
    }

    public function editTelco($id)
    {
        $telco = AutoChargingsTelco::findOrFail($id);
        // Lay ra ten cua nha cung cap can ho tro
        $autoChargingProvider = AutoChargingProvider::all();
        return view("AutoCharging::edit-telco", compact('telco','autoChargingProvider') );
    }

    public function postCreateTelco(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'key'     => 'required',
            'value' => 'required'
        ]);
        $mtopup = new AutoChargingsTelco;
        $mtopup->name = $request->input('name');
        $mtopup->key = $request->input('key');
        $mtopup->icon = $request->input('icon');
        $mtopup->description = $request->input('description');
        $mtopup->value = $request->input('value');
        if( $request->input('status') ) {
            $mtopup->status = 1;
        }else{
            $mtopup->status = 0;
        }
        $mtopup->save();
        return redirect()->route('autochargings.settings')
            ->with('success','Telco created successfully');
    }

    public function postUpdateTelco($id, Request $request )
    {
        $this->validate($request, [
            'name' => 'required',
            'key'     => 'required',
            'value' => 'required'
        ]);
        $mtopup = AutoChargingsTelco::findOrFail($id);
        $mtopup->name = $request->input('name');
        $mtopup->key = $request->input('key');
        $mtopup->icon = $request->input('icon');
        $mtopup->description = $request->input('description');
        $mtopup->value = $request->input('value');
        if( $request->input('status') )
        {
            $mtopup->status = 1;
        }else{
            $mtopup->status = 0;
        }
        $mtopup->save();
        return redirect()->route('autochargings.settings')
            ->with('success','Telco Updated successfully');
    }

    public function destroyTelco($id)
    {
        $autoChargingsTelco = AutoChargingsTelco::find($id);
        $autoChargingsTelco->delete();
        return redirect()->route('autochargings.settings')
            ->with('success','Telco deleted successfully');
    }

    public function updateFees(Request $request)
    {
        $val   = $request->input('val');
        $telco = $request->input('telco');
        $group = $request->input('group');
        $key   = $request->input('key');
        $fees = DB::table('autochargings_fees')->where('telco_key',$telco)->where('group',$group)->first();
        if( $fees )
        {
            $input[$key] = ( $val > 0 ) ? $val : 0;
            $input['updated_at'] = now();
            DB::table('autochargings_fees')->where('telco_key',$telco)->where('group',$group)->update($input);
            return response('Update Success', 200)
                ->header('Content-Type', 'text/plain');
        }else{
            $input['telco_key'] = $telco;
            $input['group'] = $group;
            $input[$key] = ( $val > 0 ) ? $val : 0;
            $input['created_at'] = now();
            $input['updated_at'] = now();
            DB::table('autochargings_fees')->insert($input);
            return response('Create Success', 200)
                ->header('Content-Type', 'text/plain');
        }
        return response('Errors', 404)
            ->header('Content-Type', 'text/plain');
    }

    public static function insertCharge($row)
    {
        $charging = new \App\Modules\AutoCharging\Models\AutoCharging;
        $charging->user = Auth::user()->id;
        $charging->user_info = Auth::user()->username;
        $charging->type = 'Charging';
        $charging->error_code = '';
        $charging->error_message = '';
        $charging->telco = $row['telco'];
        $charging->code = $row['code'];
        $charging->serial = $row['serial'];
        $charging->amount = $row['amount'] - ($row['amount']*AutoChargingFees::getFees($row['telco']))/100;
        //$charging->declared_value = $row['amount'];
        $charging->checksum = md5( $charging->code. $charging->telco. $charging->serial );
        $charging->method = 'WEB';
        $charging->request_id = '';
        $charging->description = '';
        $charging->admin_note = '';
        $charging->fees = AutoChargingFees::getFees($row['telco']);
        if( ! $charging->where('checksum', $charging->checksum)->first() )
        {
            $charging->save();
            return true;
        }else{
            return false;
        }
    }

    public static function insertChargebyUser($row, $user_id, $api_provider = NULL)
    {
        $charging = new \App\Modules\AutoCharging\Models\AutoCharging;
        $user = User::find($user_id);
        $charging->user  = $user_id;
        $charging->user_info  = $user->username;
        $charging->type  = 'Charging';
        $charging->error_code = '';
        $charging->error_message = '';
        $charging->telco = $row['telco'];
        $charging->code = $row['code'];
        $charging->serial = $row['serial'];
        $charging->amount = $row['value'] - ($row['value']*AutoChargingFees::getFeesUserId($row['telco'], $user_id))/100;
        $charging->declared_value = $row['value'];
        $charging->checksum = md5( $charging->code. $charging->telco. $charging->serial );
        $charging->method = $api_provider;
        $charging->request_id = $row['request_id'];
        $charging->description = '';
        $charging->admin_note = '';
        $charging->fees = AutoChargingFees::getFeesUserId($row['telco'], $user_id);
        if( ! $charging->where('checksum', $charging->checksum)->first() )
        {
            $charging->save();
            $result = ['trans_id' => $charging->id, 'request_id'=> $charging->request_id, 'status' => 0, 'message' =>'Thẻ đang chờ xử lý'];
            return $result;

        }else{
            return false;
        }
    }

    // Insert changrebyUser thong qua hinh thuc la API
    public static function insertChargebyUserAPI($row, $user_id, $api_provider = NULL)
    {
        $charging = new \App\Modules\AutoCharging\Models\AutoCharging;
        $user = User::find($user_id);
        $charging->user  = $user_id;
        $charging->user_info  = $user->username;
        $charging->type  = 'Charging';
        $charging->error_code = '';
        $charging->error_message = '';
        $charging->telco = $row['telco'];
        $charging->code = $row['code'];
        $charging->serial = $row['serial'];
        //$charging->amount = $row['value'] - ($row['value']*AutoChargingFees::getFeesUserId($row['telco'], $user_id))/100;
        //$charging->declared_value = $row['value'];
        $charging->checksum = md5( $charging->code. $charging->telco. $charging->serial );
        $charging->method = $api_provider;
        $charging->request_id = $row['request_id'];
        $charging->description = '';
        $charging->admin_note = '';
        $charging->fees = AutoChargingFees::getFeesUserId($row['telco'], $user_id);
        if( ! $charging->where('checksum', $charging->checksum)->first() )
        {
            $charging->save();
            $result = ['trans_id' => $charging->id, 'request_id'=> $charging->request_id, 'status' => 0, 'message' =>'Thẻ đang chờ xử lý'];
            return $result;

        }else{
            return false;
        }
    }

    /** ------------- AJAX ------------**/
    public function ajaxChargingMaster($id)
    {
        $card = AutoCharging::findOrFail($id);
        $lsAmount = AutoChargingsTelco::where('key',$card->telco)->first();
        $lsAmount = explode(',',$lsAmount->value);
        return view("AutoCharging::ajax.chargingmaster", compact('card','lsAmount'));
    }

    /*
     * THESAIMENHGIA
     */
    private function setChargingSAIMENHGIA($request, $id)
    {
        $card = AutoCharging::findOrFail($id);
        $real = $request->input('real');
        if( $card->status != 0 )
        {
            return redirect()->route('autochargings.index')
                ->withErrors(['message'=>'Bạn phải reset thẻ để thiết lập lại!']);
        }

        if( $real <=0 )
        {
            return redirect()->route('autochargings.index')
                ->withErrors(['message'=>'Bạn chưa chọn mệnh giá thực!!']);
        }
        if( $real == $card->declared_value )
        {
            return redirect()->route('autochargings.index')
                ->withErrors(['message'=>'Mệnh giá mới phải khác mệnh giá khai báo!!']);
        }
        $amount = $real - ((AutoChargingFees::getFeesUserId($card->telco, $card->user)*$real)/100);
        $amount = $amount - ((AutoChargingFees::getPenalty($card->telco, $card->user)*$real)/100);
        // Run transaction
        $trans = new Transaction;
        $trans->amount  = $amount;
        $trans->paygate_code = 'WALLET';
        $trans->admin_note = '';
        $trans->user = 1;
        $trans->userinfo = 'admin';
        $usedWallet = Wallet::getUserWallet($card->user);
        $trans_id = TransactionController::makeTransaction($trans, ['from_wallet'=>Wallet::getWalletAdmin(),'to_wallet'=>$usedWallet,'module'=>'AutoCharging', 'description'=>'THEDUNG:'.$request->input('admin_note')]);
        $runTransaction = TransactionController::runTransaction($trans_id);
        if( $runTransaction == 2 )
        {
            $card->real_value     = $request->input('real');
            $card->amount         = $amount;
            $card->status         = 2;
            $card->penalty        = AutoChargingFees::getPenalty($card->telco, $card->user);
            $card->admin_note     = $request->input('admin_note');

            $transaction_code = Transaction::where('id',$trans_id)->select('transaction_code')->first();
            $transaction_code = $transaction_code->transaction_code;
            $card->transaction_code = $transaction_code;
            $card->update();
            return redirect()->route('autochargings.index')
                ->with('success','Charging Update successfully');
        }else{
            return redirect()->route('autochargings.index')
                ->withErrors(['message'=>'Charging Update not completed']);
        }
    }

    /*
     * Set Charging DaSuDung
     */
    private function setChargingKHONGDUNGDUOC($request,$id)
    {
        $card = AutoCharging::findOrFail($id);
        if( $card->status != 0 )
        {
            return redirect()->route('autochargings.index')
                ->withErrors(['message'=>'Bạn phải reset thẻ để thiết lập lại!']);
        }
        $card->status         = 3;
        $card->admin_note     = $request->input('admin_note');
        $card->update();
        return redirect()->route('autochargings.index')
            ->with('success','Charging Update successfully');
    }

    /*
     * Set Charging DaSuDung
     */
    private function setChargingDASUDUNG($request,$id)
    {
        $card = AutoCharging::findOrFail($id);
        if( $card->status != 0 )
        {
            return redirect()->route('autochargings.index')
                ->withErrors(['message'=>'Bạn phải reset thẻ để thiết lập lại!']);
        }

        $card->status         = 4;
        $card->admin_note     = $request->input('admin_note');
        $card->update();
        return redirect()->route('autochargings.index')
            ->with('success','Charging Update successfully');
    }
    /*
     * Set Charging RIGHT
     * - Update Wallet for user
     */
    private function setChargingTHEDUNG($request, $id)
    {

        $card = AutoCharging::findOrFail($id);
        if( $card->status != 0 )
        {
            return redirect()->route('autochargings.index')
                ->withErrors(['message'=>'Bạn phải reset thẻ để thiết lập lại!']);
        }

        //Update wallet for user

        // Run transaction
        $trans = new Transaction;
        $trans->amount  = $card->amount;
        $trans->paygate_code = 'WALLET';
        $trans->admin_note = '';
        $trans->user = 1;
        $trans->userinfo = 'admin';
        $usedWallet = Wallet::getUserWallet($card->user);
        $trans_id = TransactionController::makeTransaction($trans, ['from_wallet'=>Wallet::getWalletAdmin(),'to_wallet'=>$usedWallet,'module'=>'Charging', 'description'=>'THEDUNG:'.$request->input('admin_note')]);
        $runTransaction = TransactionController::runTransaction($trans_id);
        if( $runTransaction == 2 )
        {
            $transaction_code = Transaction::where('id',$trans_id)->select('transaction_code')->first();
            $transaction_code = $transaction_code->transaction_code;
            $card->declared_value = $card->declared_value;
            $card->real_value     = $card->declared_value;
            $card->status         = 1;
            $card->admin_note     = $request->input('admin_note');
            $card->transaction_code = $transaction_code;
            $card->update();
            return redirect()->route('autochargings.index')
                ->with('success','Charging Update successfully');
        }else{
            return redirect()->route('autochargings.index')
                ->withErrors(['message'=>'Charging Update not completed']);
        }
    }

    /*
     * Destroy charging
     */
    public function destroyCharging($id)
    {
        $card = AutoCharging::findOrFail($id);
        $card->delete();
        return redirect()->route('autochargings.index')
            ->with('success','Charging deleted successfully');
    }


    // CURD settings
    public function setting(){
        $autoChargingSetting = AutoChargingSetting::all();
        //var_dump($autoChargingSetting);
        return view('AutoCharging::configsetting',compact("autoChargingSetting"));
    }

    public function getEditSetting($id){
        //echo $id;
        $autoChargingSetting = AutoChargingSetting::findOrFail($id);
        return view('AutoCharging::edit-setting',['autoChargingSetting'=>$autoChargingSetting]);
    }

    public function postUpdateSetting(Request $request, $id){
        $autoChargingSetting = AutoChargingSetting::findOrFail($id);
        $this->validate($request,[
            'meta_title'=>'required',
            'meta_description'=>'required',
            'meta_keywords'=>'required',
            'page_title'=>'required',
            'description'=>'required'
        ],[
            'meta_title.required'=>'Điển thông tin tiêu đề của web ',
            'meta_description.required'=>'Điền thông tin chi tiết ',
            'meta_keywords.required'=>'Điền từ khóa tìm kiếm ',
            'page_title.required'=>'Điền tiêu đề của page ',
            'description.required'=>'Nhập thông tin chi tiết của trang ',
        ]);
        $autoChargingSetting->meta_title = $request->meta_title;
        $autoChargingSetting->meta_description = $request->meta_description;
        $autoChargingSetting->meta_keywords = $request->meta_keywords;
        $autoChargingSetting->page_title = $request->page_title;
        $autoChargingSetting->description = $request->description;
        if($autoChargingSetting->update()){
            return redirect()->back()->with('success','Chỉnh sửa thành công');
        }else{
            return redirect()->back()->with('message','Chỉnh sửa thất bại');
        }
    }

    // pickup du lieu tu server de gui di
    public function AutoCharingSetting(){
        $title = "Cấu hình kho thẻ tự động";
        /// Kho đã được cài đặt
        $listinstalled = AutoChargingSetting::all();
        //// Kho chưa được cài đặt
        $path = app_path('Modules//Stockcard//Providers//NapTheNgay');
        $listProvider = array_map('basename', File::directories($path) );
        $list_not_installed = [];
        foreach ($listProvider as $value){
            $checkinstalled = AutoChargingSetting::where('provider', $value)->first();
            if(file_exists($path.'/'.$value.'/'.$value.'.php') && !$checkinstalled) {
                $list_not_installed[
                    ] = [
                    'name' => 'Kho thẻ '.$value,
                    'provider' => $value,
                ];
            }
        }
        return view('AutoCharging::setting', compact('title', 'list_not_installed', 'listinstalled'));
    }
    // Cai dat
    public function install($name) {
        $path = app_path('Modules//AutoCharging//Providers//NapTheNgay');
        $listProvider = array_map('basename', File::directories($path) );
        if(in_array($name, $listProvider)){
            $provider = AutoChargingSetting::where('provider', $name)->first();
            if(!$provider) {
                $ns = '\App\Modules\AutoCharging\Providers\\'. $name.'\\'.$name;
                $configp = new $ns;
                $input = [
                    'meta_title' =>'Kho thẻ '.$name,
                    'meta_description' => $name,
                    'meta_keywords' => json_encode($configp->config),
                    'page_title' => NULL,
                    'description' => 0,
                    'installed' => 1
                ];
                $result = DB::table('autochargings_setting')->insert($input);
                if($result){
                    return redirect()->route('/autochargins/setting')->with('success', 'Cài đặt kho thẻ thành công. Bạn cần sửa lại thông tin kết nối!');
                }else {
                    return 'Error insert data';
                }
            }else {
                return $name.' đã được cài đặt';
            }
        }else {
            return 'Cài đặt thất bại. Mã kho không tồn tại trong hệ thống';
        }
    }
    /*
     * Send info to Napthengay.com voi cac thong tin nhu sau:
     * 	'merchant_id'=>intval($merchant_id),
     *  'api_email'=>trim($api_email),
     *  'trans_id'=>trim($trans_id),
     *  'card_id'=>trim($mang),
     *  'card_value'=> intval($card_value),
     *  'pin_field'=>trim($sopin),
     *  'seri_field'=>trim($seri),
     *  'algo_mode'=>'hmac'
     * */
    public function checkCard($data){
        $NapTheNgay = new NapTheNgay();
        //var_dump($NapTheNgay->config);
        /*
         * Send data to API
         * Cac tham so truyen vao nhu sau
         * $arrayPost = array(
                'merchant_id'=>intval($merchant_id),
                'api_email'=>trim($api_email),
                'trans_id'=>trim($trans_id),
                'card_id'=>trim($mang),
                'card_value'=> intval($card_value),
                'pin_field'=>trim($sopin),
                'seri_field'=>trim($seri),
                'algo_mode'=>'hmac'
            );
         * voi url da duoc truyen vao tu ben Provider và tuân thủ theo mẫu sau:
         *  $data_sign = hash_hmac('SHA1',implode('',$arrayPost),$secure_code);
            $arrayPost['data_sign'] = $data_sign;
            $curl = curl_init($api_url);
            curl_setopt_array($curl, array(
                CURLOPT_POST=>true,
                CURLOPT_HEADER=>false,
                CURLINFO_HEADER_OUT=>true,
                CURLOPT_TIMEOUT=>30,
                CURLOPT_RETURNTRANSFER=>true,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_POSTFIELDS=>http_build_query($arrayPost)
            ));
            $data = curl_exec($curl);
         * */
        $arr = $NapTheNgay->config;
        $ArrayPOST = array_merge($data,$arr);
        var_dump($ArrayPOST);
    }
}
