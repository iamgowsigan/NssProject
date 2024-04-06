import 'dart:async';
import 'dart:convert';
import 'dart:io';
import 'package:flutter/material.dart';
import 'package:html_viewer_elite/html_viewer_elite.dart';
import 'package:nss/components/custom_image_viewer.dart';
import 'package:nss/router/open_screen.dart';
import 'package:nss/screens/dashboard.dart';
import 'package:nss/screens/phone_number.dart';
import 'package:nss/utils/const.dart';
import 'package:nss/utils/shared_preferences.dart';
import 'package:nss/utils/urls.dart';
import 'package:provider/provider.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:http/http.dart' as http;
import '../components/relative_scale.dart';
import '../router/none_open_screen.dart';
import '../utils/app_settings.dart';
import '../utils/style_sheet.dart';

class SplashScreen extends StatefulWidget {
  SplashScreen({Key? key}) : super(key: key);

  @override
  _SplashScreenState createState() {
    return _SplashScreenState();
  }
}

class _SplashScreenState extends State<SplashScreen> with RelativeScale {
  final _scaffoldKey = GlobalKey<ScaffoldState>();

  String phone = '';
  String exp = '';
  String name = '';
  String expert = '';
  String sid = '';
  String skipSignup = '';
  late Map data;
  List userData = [], settingData = [];

  Timer? timer;
  int counter = 0;
  int counterEnd = 3;
  bool timerDone = false;
  bool serverDone = false;
  String selectedScreen = '';
  String splashScreenImage = '';

  _SplashScreenState() {
    timer = new Timer.periodic(Duration(seconds: 1), (Timer timer) {
      setState(() {
        if (counter < counterEnd) {
          counter++;
          apiTest("test");
        } else {
          if (serverDone == false) {
            counterEnd = counterEnd + 2;
          } else {
            timerDone = true;
            openScreen(selectedScreen);
          }
        }
      });
    });
  }

  getSharedStore() async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    setState(() {
      phone = prefs.getString(Const.PHONE) ?? '';
      name = prefs.getString(Const.NAME) ?? '';
      expert = prefs.getString(Const.EXPERT) ?? '';
      exp = prefs.getString(Const.EXP) ?? '';
      sid = prefs.getString(Const.SID) ?? '';
      splashScreenImage = prefs.getString(Const.SPLASHSCREENIMAGE) ?? '';
      counterEnd = int.parse(prefs.getString(Const.SPLASHSCREENTIME) ?? '3');
      skipSignup = prefs.getString(Const.SKIP_SIGNUP_VALUE) ?? '0';
    });

    _validateUser();
  }

  _validateUser() async {
    try {
      var body = {
        "key": Const.APPKEY,
        "phone": phone,
      };
      apiTest(body.toString());
      apiTest(Urls.validation);
      final response = await http.post(Uri.parse(Urls.validation),
          headers: {HttpHeaders.acceptHeader: Const.POSTHEADER}, body: body);
      data = json.decode(response.body);
      setState(() {
        userData = data["user"];
        settingData = data["appsettings"];
        SharedStoreUtils.setValue(
            Const.SPLASHSCREENIMAGE, settingData[0]["splashscreen"]);
        SharedStoreUtils.setValue(Const.SPLASHSCREENTIME,
            settingData[0]["splashscreen_time"].toString());
        Const.APPVERSION = settingData[0]["app_version"];
        Const.OTP = settingData[0]["otp"];
        Const.TAX = settingData[0]["tax"];
        Const.APPVERSION = settingData[0]["app_version"].toString();
        Const.APP_IOS_URL = settingData[0]["ios_url"].toString();
        Const.APP_ANDROID_URL = settingData[0]["android_url"].toString();
        Const.TRANSLATION_TOKEN = settingData[0]["api_translate"].toString();
        Const.FIXER_API = settingData[0]["api_currency"].toString();
        Const.WHATSAPP_TOKEN = settingData[0]["api_whatsapp"].toString();
        Const.SMSGATEWAY = settingData[0]["api_otp"].toString();
        Const.GOOGLE_MAP_PLACE = settingData[0]["api_map"].toString();
        Const.OTPEMAIL = settingData[0]["otp_email"].toString();
        Const.OTPEMAIL_PASSWORD =
            settingData[0]["otp_email_password"].toString();
        Const.WHATSAPPNUMBER = settingData[0]["whatsapp"].toString();
        Const.Website = settingData[0]["website"].toString();
        Const.EMAIL = settingData[0]["email"].toString();
      });

      if (data["success"] == true) {
        if (skipSignup == '0') {
          apiTest('Sign in Skipped');
          if (userData.length != 0) {
            SharedStoreUtils.setValue(Const.SID, userData[0]["s_id"]);
            SharedStoreUtils.setValue(Const.NAME, userData[0]["s_name"] ?? '');
            SharedStoreUtils.setValue(Const.EXP, userData[0]["s_exp"] ?? '');

            SharedStoreUtils.setValue(Const.EXPERT, userData[0]["s_domain"] ?? '');
            SharedStoreUtils.setValue(Const.PHONE, userData[0]["s_phone"] ?? '');
            SharedStoreUtils.setValue(Const.EMAIL, userData[0]["s_email"] ?? '');
            SharedStoreUtils.setValue(
                Const.GENDER, userData[0]["s_gender"] ?? '');

            Provider.of<AppSetting>(context, listen: false).sid =
                userData[0]["s_id"] ?? '0';
            Provider.of<AppSetting>(context, listen: false).name =
                userData[0]["s_name"] ?? '';
            Provider.of<AppSetting>(context, listen: false).exp =
                userData[0]["s_exp"] ?? '';
            Provider.of<AppSetting>(context, listen: false).phone =
                userData[0]["s_phone"] ?? '';
            Provider.of<AppSetting>(context, listen: false).expert =
                userData[0]["s_domain"] ?? '';
            Provider.of<AppSetting>(context, listen: false).email =
                userData[0]["s_email"] ?? '';
            Provider.of<AppSetting>(context, listen: false).gender =
                userData[0]["s_gender"] ?? '';

            if (userData[0]["s_name"].toString() != "null" &&
                userData[0]["s_name"].toString() != "") {

              selectedScreen = '';
            } else {
              selectedScreen = "phone";
            }
          } else {
            selectedScreen = "phone";
          }
          apiTest('test skip $selectedScreen');
        } else {
          selectedScreen = '';
        }
      } else {
        selectedScreen = 'phone';
      }
      setState(() {
        serverDone = true;
      });
    } catch (e) {
      print(e);
      updatePopup();
    }
  }

  openScreen(String screen) {
    timer!.cancel();
    apiTest(screen + 'screen');
    if (screen == "phone") {
      Navigator.pushReplacement(
          context, OpenScreen(widget: PhoneNumberScreen()));
    } else {

      Navigator.pushReplacement(context, NoneOpenScreen(widget: Dashboard()));
    }
  }

  updatePopup() {
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.all(Radius.circular(20.0))),
        title: Text(
          "Attention",
          textAlign: TextAlign.center,
          style: TextStyle(
            fontWeight: FontWeight.bold,
            fontSize: sy(n),
            color: fc_1,
          ),
        ),
        content: IntrinsicWidth(
          child: IntrinsicHeight(
            child: Column(
              children: [
                Text(
                  "Check your Connection",
                  style: TextStyle(
                    color: fc_2,
                    fontSize: sy(n),
                  ),
                ),
                SizedBox(
                  height: 12,
                ),
                ElevatedButton(
                    onPressed: () {},
                    style: elevatedButton(ThemePrimary, 5),
                    child: Container(
                      width: MediaQuery.of(context).size.width,
                      height: 35,
                      alignment: Alignment.center,
                      child: Text(
                        "Update",
                        style: TextStyle(
                          color: fc_2,
                          fontSize: sy(n),
                        ),
                      ),
                    )),
              ],
            ),
          ),
        ),
      ),
    );
  }

  @override
  void initState() {
    super.initState();
    getSharedStore();
  }

  @override
  void dispose() {
    super.dispose();
    timer!.cancel();
  }

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      debugShowCheckedModeBanner: false,
      theme: MyThemes.lightTheme,
      darkTheme: MyThemes.darkTheme,
      home: Scaffold(
        resizeToAvoidBottomInset: false,
        key: _scaffoldKey,
        body: Container(
          color: StatusBarColor,
          width: MediaQuery.of(context).size.width,
          height: MediaQuery.of(context).size.height,
          child: Stack(
            children: [
              if (splashScreenImage == '')
                Positioned(
                  left: 0,
                  right: 0,
                  top: 0,
                  bottom: 0,
                  child: Container(
                    width: MediaQuery.of(context).size.width,
                    height: MediaQuery.of(context).size.height,
                    alignment: Alignment.center,
                    child: Column(
                      mainAxisAlignment: MainAxisAlignment.center,
                      crossAxisAlignment: CrossAxisAlignment.center,
                      children: [
                        Image.asset(
                          "assets/images/splash.jpg",
                          width: MediaQuery.of(context).size.width,
                          height: MediaQuery.of(context).size.height,
                          fit: BoxFit.contain,
                        ),
                      ],
                    ),
                  ),
                ),
              if (splashScreenImage != '')
                Positioned(
                  left: 0,
                  right: 0,
                  bottom: 0,
                  top: 0,
                  child: Container(
                    width: MediaQuery.of(context).size.width,
                    height: MediaQuery.of(context).size.height,
                    alignment: Alignment.center,
                    child: CustomeImageView(
                      image: Urls.imageLocation + splashScreenImage,
                      isTrans: true,
                      width: MediaQuery.of(context).size.width,
                      height: MediaQuery.of(context).size.height,
                      fit: BoxFit.cover,
                    ),
                  ),
                ),
            ],
          ),
        ),
      ),
    );
  }
}
