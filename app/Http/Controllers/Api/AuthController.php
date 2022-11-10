<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Http\Resources\UserProfileCollection;
use App\Models\MentorEducation;
use App\Models\PatientDisease;
use App\Models\PatientHistory;
use App\Models\PatientInterest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
// use App\Http\Resources\UserProfileCollection;
use App\Models\User;
use Auth;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Validator;

class AuthController extends BaseController
{
	// UNAUTHORIZED ACCESS
	public function unauthorized_access()
	{
		return $this->sendFailed('YOU ARE NOT UNAUTHORIZED TO ACCESS THIS URL, PLEASE LOGIN AGAIN', 401);
	}


	// CREATE ACCOUNT API
	public function register(Request $request)
	{
		$error_message = 	[
			'first_name.required'			  => 'First Name should be required',
			'last_name.required'			  => 'Last Name should be required',
			'mobile.unique'  	              => 'mobile has been already taken',
			'mobile.required'            	  => 'Mobile should be required',
			'role.required'			 	  	  => 'User Type should be required',
			'role.In'						  => 'User Type should be 2 OR 3',
		];
		$rules = [
			'mobile'                          => 'required|digits:10',
			'role'						      => 'required|In:2,3',
			'first_name'					  => 'required|max:20',
			'last_name'					      => 'required|max:20',
			'profile_pic' 					  => 'required|mimes:jpg,jpeg,png|max:2048'
		];
		$validator = Validator::make($request->all(), $rules, $error_message);
		if ($validator->fails()) {
			return $this->sendFailed($validator->errors()->first(), 200);
		}
		$checkMobile = User::where(['mobile' => $request->mobile, 'role' => $request->role])->first();
		if (!empty($checkMobile)) {
			if ($checkMobile->otp_status == 1) {
				return $this->sendFailed('Mobile has been already taken', 200);
			}
		}
		try {
			\DB::beginTransaction();
			$user_pic = time() . '_' . rand(1111, 9999) . '.' . $request->file('profile_pic')->getClientOriginalExtension();
			$request->file('profile_pic')->storeAs('user_images', $user_pic, 'public');
			$request['profile_pic'] = $user_pic;
			if (!empty($checkMobile)) {
				$user = User::find($checkMobile->id);
			} else {
				$user = new User();
			}
			$user->fill($request->all());
			$user->profile_pic  = $user_pic;
			$user->save();
			$user->assignRole([$user->role]);
			$verifaction_otp = rand(1000, 9999);
			$user->otp = $verifaction_otp;
			$user->save();
			Self::send_sms_otp($request->mobile, $verifaction_otp);
			\DB::commit();
			return $this->sendSuccess('OTP SENT SUCCESSFULLY', ['user_id' => $user->id, 'otp' => $verifaction_otp]);
		} catch (\Throwable $e) {
			\DB::rollback();
			return $this->sendFailed($e->getMessage() . ' on line ' . $e->getLine(), 400);
		}
	}

	// OTP VERIFY

	public function registerOtpVerify(Request $request)
	{
		$error_message = 	[
			'mobile.required'            	  => 'Mobile should be required',
			'user_id.required'			 	  => 'User Id should be required',
			'otp.required'					  => 'OTP should be required',
		];
		$rules = [
			'mobile'                          => 'required|integer|exists:users|digits:10',
			'user_id'						  => 'required|integer|exists:users,id',
			'otp'						      => 'required|integer|digits:4'
		];
		$validator = Validator::make($request->all(), $rules, $error_message);
		if ($validator->fails()) {
			return $this->sendFailed($validator->errors()->first(), 200);
		}
		try {
			\DB::beginTransaction();

			$user_detail = User::find($request->user_id);
			if ($user_detail->mobile != $request->mobile) {
				return $this->sendFailed("Mobile number does not exist", 200);
			}
			if ($request->otp != $user_detail->otp) {
				return $this->sendFailed("wrong otp", 200);
			}

			Auth::loginUsingId($user_detail->id);
			$user_detail->otp = rand(1000, 9999);
			$user_detail->otp_status = 1;
			$user_detail->save();
			$access_token = auth()->user()->createToken(auth()->user()->mobile)->accessToken;
			\DB::commit();
			return $this->sendSuccess('LOGGED IN SUCCESSFULLY', ['access_token' => $access_token, 'profile_data' => new UserProfileCollection(auth()->user())]);
		} catch (\Throwable $e) {
			\DB::rollback();
			return $this->sendFailed($e->getMessage() . ' on line ' . $e->getLine(), 400);
		}
	}

	// UPDATE USER OTHER DETAILS
	public function updateUserOtherDetail(Request $request)
	{
		$error_message = 	[
			'email.required'            	  => 'E-mail should be required',
			'email.unique'			 	      => 'E-mail  should be already taken',
			'dob.required'					  => 'DOB should be required',
		];
		$rules = [
			'email'                           => 'required|email|unique:users,email,' . auth()->user()->id,
			'dob'						      => 'required|date',
			'gender'						  => 'required|In:Male,Female,Other',
			'patient_diseases'        		  => 'required|array',
			'patient_interests'        		  => 'required|array',
			'patient_histories'        		  => 'required|array',
			'bio'						      => 'required',
		];
		$validator = Validator::make($request->all(), $rules, $error_message);
		if ($validator->fails()) {
			return $this->sendFailed($validator->errors()->first(), 200);
		}
		try {
			$user_details = auth()->user();
			\DB::beginTransaction();

			if (is_array($request->patient_diseases)) {
				$diseases   		= new PatientDisease();
				$diseases->title	= json_encode($request->patient_diseases);
				auth()->user()->patientDisease()->save($diseases);
			}

			if (is_array($request->patient_interests)) {
				$interest   		= new PatientInterest();
				$interest->title	= json_encode($request->patient_interests);
				auth()->user()->patientDisease()->save($interest);
			}

			if (is_array($request->patient_histories)) {
				$history   		= new PatientHistory();
				$history->title	= json_encode($request->patient_histories);
				auth()->user()->patientDisease()->save($history);
			}
			$request['dob']   = date('Y-m-d', strtotime($request->dob));
			$user_details->fill($request->all())->save();
			\DB::commit();
			return $this->sendSuccess('UPDATE USER OTHER DETAIL SUCCESSFULLY');
		} catch (\Throwable $e) {
			\DB::rollback();
			return $this->sendFailed($e->getMessage() . ' on line ' . $e->getLine(), 400);
		}
	}



	// UPDATE USER OTHER DETAILS
	public function updateMentorOtherDetail(Request $request)
	{
		$error_message = 	[
			'email.required'            	  => 'E-mail should be required',
			'email.unique'			 	      => 'E-mail  should be already taken',
			'dob.required'					  => 'DOB should be required',
		];
		$rules = [
			'email'                           => 'required|email|unique:users,email,' . auth()->user()->id,
			'dob'						      => 'required|date',
			'gender'						  => 'required|In:Male,Female,Other',
			'mentor_educations'        		  => 'required|array',
			'mentor_interests'        		  => 'required|array',			
			'bio'						      => 'required',
		];
		$validator = Validator::make($request->all(), $rules, $error_message);
		if ($validator->fails()) {
			return $this->sendFailed($validator->errors()->first(), 200);
		}
		try {
			$user_details = auth()->user();
			\DB::beginTransaction();

			if (is_array($request->mentor_educations)) {
				$education   		= new MentorEducation();
				$education->title	= json_encode($request->mentor_educations);
				auth()->user()->patientDisease()->save($education);
			}

			if (is_array($request->mentor_interests)) {
				$interest   		= new PatientInterest();
				$interest->title	= json_encode($request->mentor_interests);
				auth()->user()->patientDisease()->save($interest);
			}

			
			$request['dob']   = date('Y-m-d', strtotime($request->dob));
			$user_details->fill($request->all())->save();
			\DB::commit();
			return $this->sendSuccess('UPDATE MENTOR OTHER DETAIL SUCCESSFULLY');
		} catch (\Throwable $e) {
			\DB::rollback();
			return $this->sendFailed($e->getMessage() . ' on line ' . $e->getLine(), 400);
		}
	}

	

	public function updatePassword(Request $request)
	{
		$error_message = 	[
			'password.required'            	  => 'Password should be required',
		];
		$rules = [
			'password'						  => 'required',

		];
		$validator = Validator::make($request->all(), $rules, $error_message);
		if ($validator->fails()) {
			return $this->sendFailed($validator->errors()->first(), 200);
		}
		try {
			// echo auth()->user()->id;die;
			\DB::beginTransaction();
			$password = $request->password;
			$user = auth()->user();
			$user->password = Hash::make($request->password);
			$user->save();
			\DB::commit();
			return $this->sendSuccess('PASSWORD UPDATE SUCCESSFULLY');
		} catch (\Throwable $e) {
			\DB::rollback();
			return $this->sendFailed($e->getMessage() . ' on line ' . $e->getLine(), 400);
		}
	}

	public function login(Request $request)
	{
		// echo Auth::user();die;
		$error_message = 	[
			'username.required'     => 'Email address should be required',
			'password.required' 	=> 'Password should be required',
			'role.required'     	=> 'User role should be required',
		];
		$rules = [
			'username'        		=> 'required',
			'password'          	=> 'required',
			'role'              	=> 'required|In:2,3',
		];
		$validator = Validator::make($request->all(), $rules, $error_message);
		if ($validator->fails()) {
			return $this->sendFailed(implode(", ", $validator->errors()->all()), 200);
		}
		try {
			// auth()->attempt(['email' => $request->username, 'password' => $request->password, 'role' => $request->role]) || 
			// echo $request->password;die;
			if (auth()->attempt(['mobile' => $request->username, 'password' => $request->password, 'role' => $request->role]) || auth()->attempt(['email' => $request->username, 'password' => $request->password, 'role' => $request->role])) {
				
				\DB::beginTransaction();

				$access_token       = auth()->user()->createToken(auth()->user()->name)->accessToken;
				\DB::commit();
				return $this->sendSuccess('LOGGED IN SUCCESSFULLY', ['access_token' => $access_token, 'profile_data' => new UserProfileCollection(auth()->user())]);
			} else {
				return $this->sendFailed('YOUR LOGIN CREDITIALS IS INVALID. PLEASE TRY AGAIN', 200);
			}
		} catch (\Throwable $e) {
			\DB::rollback();
			return $this->sendFailed($e->getMessage() . ' on line ' . $e->getLine(), 400);
		}
	}



	public function getUserProfile()
	{
		return $this->sendSuccess('LOGGED IN SUCCESSFULLY', ['profile_data' => new UserProfileCollection(auth()->user())]);
	}


	public function reSentOtpMobileUpdate(Request $request)
	{
		$error_message = 	[
			'mobile.required'  	=> 'Mobile address should be required',
		];
		$rules = [
			'mobile' => 'required|unique:users,mobile,' . auth()->user()->id,
		];
		$validator = Validator::make($request->all(), $rules, $error_message);
		if ($validator->fails()) {
			return $this->sendFailed($validator->errors()->first(), 200);
		}
		$mobileExist = User::where('id', '!=', auth()->user()->id)->where(['role' => auth()->user()->role, 'mobile' => $request->mobile])->count();
		if ($mobileExist > 0) {
			return $this->sendFailed("Mobile number has been already taken", 200);
		}
		try {
			$verifaction_otp = rand(1000, 9999);
			Self::send_sms_otp($request->mobile, $verifaction_otp);
			$user = auth()->user();
			$user->otp = $verifaction_otp;
			$user->save();
			return $this->sendSuccess('OTP SENT SUCCESSFULLY', ['verifaction_otp' => $verifaction_otp, 'mobile' => $request->mobile]);
		} catch (\Throwable $e) {
			return $this->sendFailed($e->getMessage() . ' on line ' . $e->getLine(), 400);
		}
	}

	public function registerReSentOtp(Request $request)
	{
		$error_message = 	[
			// 'mobile.unique'  	              => 'mobile has been already taken',
			'mobile.required'            	  => 'Mobile should be required',
			'user_id.required'			 	  => 'User Id should be required',
		];
		$rules = [
			'mobile'                          => 'required|min:10|max:10|exists:users,mobile',
			'user_id'						  => 'required|integer|exists:users,id',
		];
		$validator = Validator::make($request->all(), $rules, $error_message);
		if ($validator->fails()) {
			return $this->sendFailed($validator->errors()->first(), 200);
		}
		$user_detail = User::find($request->user_id);
		if ($user_detail->mobile != $request->mobile) {
			return $this->sendFailed("Mobile number does not exist", 200);
		}
		try {
			$verifaction_otp = rand(1000, 9999);
			Self::send_sms_otp($request->mobile, $verifaction_otp);
			$user_detail->otp = $verifaction_otp;
			$user_detail->save();
			Self::send_sms_otp($request->mobile, $verifaction_otp);
			\DB::commit();
			return $this->sendSuccess('OTP SENT SUCCESSFULLY', ['user_id' => $user_detail->id, 'otp' => $verifaction_otp]);
		} catch (\Throwable $e) {
			return $this->sendFailed($e->getMessage() . ' on line ' . $e->getLine(), 400);
		}
	}

	public function updateMobile(Request $request)
	{
		$error_message = 	[
			'mobile.required'  	=> 'Mobile should be required',
			'OTP.required'  	=> 'OTP  should be required',
		];
		$rules = [
			'mobile'            => 'required|min:10|max:10',
			'otp'               => 'required|min:4|max:4',
		];
		$validator = Validator::make($request->all(), $rules, $error_message);
		if ($validator->fails()) {
			return $this->sendFailed($validator->errors()->first(), 200);
		}
		$mobileExist = User::where('id', '!=', auth()->user()->id)->where(['role' => auth()->user()->role, 'mobile' => $request->mobile])->count();
		if ($mobileExist > 0) {
			return $this->sendFailed("Mobile number has been already taken", 200);
		}

		if (auth()->user()->otp != $request->otp) {
			return $this->sendFailed("wrong otp", 200);
		}

		try {
			\DB::beginTransaction();
			$user_details = auth()->user();
			$user_details->mobile = $request->mobile;
			$user_details->otp = null;
			$user_details->save();
			\DB::commit();
			return $this->sendSuccess('MOBILE NUMBER UPDATE SUCCESSFULLY');
		} catch (\Throwable $e) {
			\DB::rollback();
			return $this->sendFailed($e->getMessage() . ' on line ' . $e->getLine(), 400);
		}
	}

	// UPDATE PROFILE
	public function updateUserProfile(Request $request)
	{
		$error_message = 	[
			'name.required'    	 => 'Name should be required',
			'email.required'	 => 'Email address should be required',
			'address.required'	 => 'Address should be required',
			'email.unique'  	 => 'Email address has been taken',
			'profile_pic.mimes'  => 'Profile photo format jpg,jpeg,png',
			'profile_pic.max'    => 'Profile photo max size 2 MB',
			'dob.required'		 => 'Date Of Birth should be required.',
			'dob.required'		 => 'Date Of Birth should be valid date format.'
		];
		$rules = [
			'name'            => 'required|max:20',
			'email' 		  => 'required|email',
			'mobile'          => 'required|min:10|max:10',
			'dob'			  => 'required|date',
			'address'		  => 'required',
		];
		if (!empty($request->profile_pic)) {
			$rules['profile_pic'] = 'mimes:jpg,jpeg,png|max:2000';
		}
		$validator = Validator::make($request->all(), $rules, $error_message);
		if ($validator->fails()) {
			return $this->sendFailed($validator->errors()->first(), 200);
		}

		$mobileExist = User::where('id', '!=', auth()->user()->id)->where(['role' => auth()->user()->role, 'mobile' => $request->mobile])->count();
		if ($mobileExist > 0) {
			return $this->sendFailed("Mobile number has been already taken", 200);
		}

		$emailExist = User::where('id', '!=', auth()->user()->id)->where(['role' => auth()->user()->role, 'email' => $request->email])->count();
		if ($emailExist > 0) {
			return $this->sendFailed("Email address has been already taken", 200);
		}

		try {
			\DB::beginTransaction();
			$user_details = auth()->user();
			$user_details->fill($request->only('name', 'email', 'address', 'dob', 'address'));
			if (!empty($request->file('profile_pic'))) {
				if (Storage::disk('public')->exists('user_images/' . $user_details->user_pic_name)) {
					Storage::disk('public')->delete('user_images/' . $user_details->user_pic_name);
				}
				$user_pic = time() . '_' . rand(1111, 9999) . '.' . $request->file('profile_pic')->getClientOriginalExtension();
				$request->file('profile_pic')->storeAs('user_images', $user_pic, 'public');
				$request['profile_pic'] = $user_pic;
				$user_details->profile_pic = $user_pic;
			}
			$user_details->save();
			\DB::commit();
			if ($request->mobile == auth()->user()->mobile) {
				$mobileVerify = array('mobile_verify_status' => 1);
				return $this->sendSuccess('PROFILE UPDATE SUCCESSFULLY', $mobileVerify);
			} else {
				$otp = rand(1000, 9999);
				Self::send_sms_otp($request->mobile, $otp);
				$user = auth()->user();
				$verifaction_otp = $otp;
				$user->otp = $verifaction_otp;
				$user->save();
				$mobileVerify = array('mobile_verify_status' => 0, 'otp' => $otp);
				return $this->sendSuccess('PROFILE UPDATE AND OTP SENT SUCCESSFULLY YOUR MOBILE', $mobileVerify);
			}
		} catch (\Throwable $e) {
			\DB::rollback();
			return $this->sendFailed($e->getMessage() . ' on line ' . $e->getLine(), 400);
		}
	}

	public function forgot_password(Request $request)
	{
		$error_message = 	[
			'email.required'    => 'Email address should be required',
			'email.exists'      => 'WE COULD NOT FOUND ANY EMAIL'
		];
		$rules = [
			'email'       		=> 'required|email|exists:users,email',
		];
		$validator = Validator::make($request->all(), $rules, $error_message);
		if ($validator->fails()) {
			return $this->sendFailed($validator->errors()->first(), 200);
		}
		try {
			$user_detail = User::where('email', $request->email)->first();
			if (!isset($user_detail)) {
				return $this->sendFailed('WE COULD NOT FOUND ANY ACCOUNT', 200);
			}
			$verifaction_otp = rand(1000, 9999);
			$email_data = ['user_name' => $user_detail->first_name, 'verifaction_otp' => $verifaction_otp];
			\Mail::to($user_detail->email)->send(new \App\Mail\ForgotPassword($email_data));
			return $this->sendSuccess('OTP SENT SUCCESSFULLY', ['user_id' => $user_detail->id, 'verifaction_otp' => $verifaction_otp, 'email' => $user_detail->email]);
		} catch (\Throwable $e) {
			return $this->sendFailed($e->getMessage() . ' on line ' . $e->getLine(), 400);
		}
	}

	public function reset_password(Request $request)
	{
		$error_message = 	[
			'id.required'  		=> 'Id should be required',
			'password.required' => 'Password should be required',
		];
		$rules = [
			'id'        		=> 'required|numeric|exists:users,id',
			'password'      	=> 'required',
		];
		$validator = Validator::make($request->all(), $rules, $error_message);
		if ($validator->fails()) {
			return $this->sendFailed($validator->errors()->first(), 200);
		}
		try {
			$user_detail = User::find($request->id);
			if (!isset($user_detail)) {
				return $this->sendFailed('WE COULD NOT FOUND ANY ACCOUNT', 200);
			}
			\DB::beginTransaction();
			$user_detail->password = Hash::make($request->user_password);
			$user_detail->save();
			\DB::commit();
			return $this->sendSuccess('PASSWORD UPDATED SUCCESSFULLY');
		} catch (\Throwable $e) {
			\DB::rollback();
			return $this->sendFailed($e->getMessage() . ' on line ' . $e->getLine(), 400);
		}
	}

	public function send_sms_otp($mobile_number, $verification_otp)
	{

		// $opt_url = "https://2factor.in/API/V1/fd9c6a99-19d7-11ec-a13b-0200cd936042/SMS/" . $mobile_number . "/" . $verification_otp . "/OTP_TAMPLATE";
		// $curl = curl_init();
		// curl_setopt($curl, CURLOPT_URL, $opt_url);
		// curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		// curl_setopt($curl, CURLOPT_PROXYPORT, "80");
		// curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		// $result = curl_exec($curl);
		return;
	}


	public function changeOnlineStatus()
	{
		try {
			\DB::beginTransaction();
			$change = User::find(auth()->user()->id)->update(['online_status' => auth()->user()->online_status == 'Online' ? 'Offline' : 'Online']);
			\DB::commit();
			return $this->sendSuccess('Online Status change succssfully');
		} catch (\Throwable $e) {
			\DB::rollback();
			return $this->sendFailed($e->getMessage() . ' on line ' . $e->getLine(), 400);
		}
	}
}
