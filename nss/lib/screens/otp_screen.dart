import 'dart:async';
import 'dart:convert';
import 'dart:io';
import 'package:nss/components/relative_scale.dart';
import 'package:flutter/material.dart';
import 'package:nss/router/open_screen.dart';
import 'package:nss/router/right_open_screen.dart';
import 'package:nss/screens/dashboard.dart';
import 'package:nss/screens/phone_number.dart';
import 'package:nss/utils/const.dart';
import 'package:provider/provider.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:http/http.dart' as http;
import '../components/pop.dart';
import '../library/otp_field.dart';
import '../model/save_user_data.dart';
import '../utils/app_settings.dart';
import '../utils/shared_preferences.dart';
import '../utils/style_sheet.dart';
import '../utils/urls.dart';

class OtpScreen extends StatefulWidget {
  final getPhone;
  final getEmail;
  final country;
  final skipIntro;
  final popLogin;
  //final reg_no;
  OtpScreen(
      {this.getPhone = '',
      this.getEmail = '',
      this.country,
       // this.reg_no = '',
      this.skipIntro = false,
      this.popLogin = false,
      Key? key})
      : super(key: key);

  @override
  State<OtpScreen> createState() => _OtpScreenState();
}

class _OtpScreenState extends State<OtpScreen> with RelativeScale {
  final _scaffoldKey = GlobalKey<ScaffoldState>();
  bool showProgress = false;
  Map data = Map();
  List userDetail = [];
  String UserId = '',
      getOtp = '',
      oldOtp = '',
      getPhone = '',
      state = '',
      expert = '',
      exp = '';
      //reg_no = '';
  bool showRegistration = false;
  TextEditingController ETname = TextEditingController();
  TextEditingController ETcity = TextEditingController();
  TextEditingController ETphone = TextEditingController();
  TextEditingController ETemail = TextEditingController();
  Timer? _timer;
  int _start = 60;

  //Logins
  bool googleLoading = false;
  bool fbLoading = false;
  Map fbUser = Map();
  bool enableFields = true;
  bool socialSuccess = false;
  bool _keyboardVisible = false;
  String gender = 'male';
  String expertLevel = '1';
  String profilePic = 'default/user2.jpg';
  int profileSlide = 2;

  void startTimer() {
    _timer = Timer.periodic(Duration(seconds: 1), (Timer timer) {
      if (_start == 0) {
        setState(() {
          timer.cancel();
        });
      } else {
        setState(() {
          _start--;
        });
      }
    });
  }

  @override
  void initState() {
    super.initState();
    getSharedStore();
    startTimer();
  }

  @override
  void dispose() {
    super.dispose();
    _timer!.cancel();
  }

  getSharedStore() async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    setState(() {
      UserId = prefs.getString(Const.SID) ?? '';
      oldOtp = prefs.getString(Const.OTP) ?? '';
      exp = prefs.getString(Const.EXP) ?? '';
      expert = prefs.getString(Const.EXPERT) ?? '0';
      getPhone = widget.getPhone;
    });
  }

  _checkOTP() async {
    apiTest(Const.OTP);
    apiTest(getOtp);
    if (getOtp == 2003 || getOtp == Const.OTP) {
      setState(() {
        showProgress = true;
      });
      var body = {
        "key": Const.APPKEY,
        "code": widget.country.toString(),
        "phone": getPhone.toString(),
        "email": widget.getEmail.toString(),
      };
      final response = await http.post(Uri.parse(Urls.GetUser),
          headers: {HttpHeaders.acceptHeader: Const.POSTHEADER}, body: body);
      apiTest(response.request.toString());
      apiTest(body.toString());
      data = json.decode(response.body);
      setState(() {
        showProgress = false;
      });

      setState(() {
        if (data["success"] == true) {
          if (data["new"] == false) {
            userDetail = data["user"];
            String sid = userDetail[0]["s_id"].toString();
            String mobile = userDetail[0]["s_phone"].toString();
            String expert = userDetail[0]["s_domain"].toString();
            String email = userDetail[0]["s_email"].toString();
            String exp = userDetail[0]["s_exp"].toString();



            SaveUserData(context,userDetail);
            SharedStoreUtils.setValue(Const.SKIP_SIGNUP_VALUE, '0');
            SharedStoreUtils.setValue(Const.SID, sid);
            SharedStoreUtils.setValue(Const.PHONE, mobile);
            SharedStoreUtils.setValue(Const.EXPERT, expert);
            SharedStoreUtils.setValue(Const.EMAIL, email);
            SharedStoreUtils.setValue(Const.EXP, exp);
            Provider.of<AppSetting>(context, listen: false).skipSignup ='0';

            apiTest('exp data: '+exp);

            if (userDetail[0]["s_name"].toString() == "null") {
              setState(() {

                //showRegistration = true;
                Pop.errorTop(context, 'User not Register with us');
                Navigator.pushReplacement(context, RightOpenScreen(widget: PhoneNumberScreen(skipIntro: true, popLogin: widget.popLogin,)));
              });
            } else {
              if (widget.popLogin == true) {


                Navigator.of(context).pop();
                apiTest('closing');
              } else {
                openScreen();
              }
            }
          }else{
            Pop.errorTop(context, 'User not Register with us');
            Navigator.pushReplacement(context, RightOpenScreen(widget: PhoneNumberScreen(skipIntro: true, popLogin: widget.popLogin,)));
          }

        } else {
          Pop.errorTop(context, "Something wrong", );
        }
      });
    } else {
      Pop.errorTop(context, "Wrong OTP", );
    }
  }

  @override
  Widget build(BuildContext context) {
    initRelativeScaler(context);
    _keyboardVisible = MediaQuery.of(context).viewInsets.bottom != 0;
    return Container(
      child: SafeArea(
        top: false,
        child: Scaffold(
          key: _scaffoldKey,
          body: Container(
            width: MediaQuery.of(context).size.width,
            height: MediaQuery.of(context).size.height,
            child: Stack(
              children: [
                if (showRegistration == false)
                  Positioned(
                    bottom: 0,
                    top: 0,
                    left: 0,
                    right: 0,
                    child: otpWidget(),
                  ),
                if (showRegistration == true)
                  Positioned(
                    bottom: 0,
                    top: 0,
                    left: 0,
                    right: 0,
                    child: registerWidget(),
                  ),
                Positioned(
                  top: MediaQuery.of(context).padding.top + sy(10),
                  left: sy(10),
                  child: Container(
                    child: GestureDetector(
                      onTap: () {
                        if (widget.popLogin == false) {
                          Navigator.pushReplacement(
                              context,
                              RightOpenScreen(
                                  widget: PhoneNumberScreen(
                                skipIntro: true,
                                popLogin: widget.popLogin,
                              )));
                        } else {
                          Navigator.of(context).maybePop();
                        }
                      },
                      child: Material(
                        color: fc_bg,
                        borderRadius: BorderRadius.circular(sy(20)),
                        child: Container(
                          padding:
                              EdgeInsets.fromLTRB(sy(0), sy(0), sy(0), sy(0)),
                          child: Icon(
                            Icons.arrow_back,
                            color: fc_1,
                            size: sy(xl - 1),
                          ),
                        ),
                      ),
                    ),
                  ),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }

  otpWidget() {
    return Container(
      width: MediaQuery.of(context).size.width,
      height: MediaQuery.of(context).size.height,
      color: fc_bg,
      padding: EdgeInsets.fromLTRB(
          sy(15), MediaQuery.of(context).padding.top + sy(5), sy(15), sy(5)),
      child: SingleChildScrollView(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.start,
          crossAxisAlignment: CrossAxisAlignment.center,
          children: [
            SizedBox(
              height: sy(40),
            ),
            Image.asset(
              'assets/images/logo.jpg',
              width: sy(70),
              height: sy(70),
            ),
            SizedBox(
              height: sy(10),
            ),
            Text(
              'Verification',
              style: TextStyle(
                color: fc_1,
                fontWeight: FontWeight.bold,
                fontSize: sy(l + 1),
              ),
            ),
            SizedBox(
              height: sy(8),
            ),
            Text(
              'Please enter OTP which is sent to your',
              style: TextStyle(
                color: fc_3,
                fontSize: sy(s),
              ),
            ),
            SizedBox(
              height: sy(2),
            ),
            Text(
              widget.getEmail == ''
                  ? widget.country + widget.getPhone
                  : widget.getEmail,
              style: TextStyle(
                color: Colors.blue,
                fontSize: sy(s),
              ),
            ),
            SizedBox(height: sy(15),),

            Container(
              margin: EdgeInsets.fromLTRB(sy(0), sy(0), sy(0), sy(0)),
              width: sy(220),
              height: sy(35),
              //width: Width(context)*0.7,
              child: OTPTextField(
                length: 4,
                width: MediaQuery.of(context).size.width,
                fieldWidth:sy(40),
                style: TextStyle(
                  fontSize: sy(l),
                    color: fc_1,
                ),
                textFieldAlignment: MainAxisAlignment.spaceEvenly,
                fieldStyle: FieldStyle.box,
                keyboardType: TextInputType.number,
                otpFieldStyle: OtpFieldStyle(
                  focusBorderColor: fc_textfield_bg,
                  enabledBorderColor: fc_textfield_bg,
                  disabledBorderColor: fc_textfield_bg,
                  backgroundColor: fc_textfield_bg,
                  borderColor: fc_textfield_bg,),
                onCompleted: (pin) {
                  setState(() {
                    getOtp = pin.toString();
                  });
                  _checkOTP();
                },
              ),
            ),
          ],
        ),
      ),
    );
  }

  registerWidget() {
    return Container();
  }

  void openScreen() {
    Navigator.pushReplacement(context, OpenScreen(widget: Dashboard()));
  }
}
