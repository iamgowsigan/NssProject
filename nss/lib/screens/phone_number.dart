import 'dart:convert';
import 'dart:io';
import 'package:nss/library/random_string.dart';
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:mailer/mailer.dart';
import 'package:mailer/smtp_server/gmail.dart';
import 'package:nss/components/progress_button.dart';
import 'package:nss/components/relative_scale.dart';
import 'package:nss/components/widget_help.dart';
import 'package:nss/router/left_open_screen.dart';
import 'package:nss/screens/otp_screen.dart';
import 'package:nss/utils/style_sheet.dart';
import 'package:http/http.dart' as http;
import 'package:nss/utils/urls.dart';
import 'package:provider/provider.dart';
import 'package:shared_preferences/shared_preferences.dart';
import '../components/pop.dart';
import '../library/country.dart';
import '../library/location/country_screen.dart';
import '../router/open_screen.dart';
import '../utils/app_settings.dart';
import '../utils/const.dart';
import '../utils/shared_preferences.dart';

class PhoneNumberScreen extends StatefulWidget {
  bool skipIntro;
  String phone;
  String country;
  bool popLogin;
  PhoneNumberScreen(
      {Key? key, this.skipIntro = false, this.phone = '', this.country = '', this.popLogin=false})
      : super(key: key);

  @override
  State<PhoneNumberScreen> createState() => _PhoneNumberScreenState();
}

class _PhoneNumberScreenState extends State<PhoneNumberScreen>
    with RelativeScale {
  final _scaffoldKey = GlobalKey<ScaffoldState>();
  bool showProgress = false;
  Map? data = Map();
  String? UserId;
  late PageController pageController;
  bool _keyboardVisible = false;
  String country = World.Countries[100]['phonecode'].toString();
  TextEditingController ETphone = TextEditingController();
  TextEditingController ETemail = TextEditingController();
  String errMsg = '';
  String myOTP = '2003';
  bool email = false;
  bool validPhone = false;
  bool validEmail = false;

  getSharedStore() async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    setState(() {
      UserId = prefs.getString(Const.SID);
    });
  }

  _checkEmail() async {
    setState(() {
      showProgress = true;
    });
    final response = await http.post(Uri.parse(Urls.GetUserEmail), headers: {
      HttpHeaders.acceptHeader: Const.POSTHEADER
    }, body: {
      "key": Const.APPKEY,
      "email": ETemail.text.toString(),
    });

    data = json.decode(response.body);
    setState(() {
      showProgress = false;
    });
    setState(() {
      if (data!["success"] == true) {
        if (data!["old"] == true) {
          errMsg = '';
          apiTest("Test passed");
          _sendEmail();
        } else {
          errMsg = 'Email not registered with us';
          apiTest("Test not passed");
        }
      } else {
        Pop.errorTop(context, 'Something went wrong');
      }
    });
  }

  _sendEmail() async {
    setState(() {
      showProgress = true;
    });
    apiTest('Email');
    apiTest('Email${Const.OTPEMAIL}');
    apiTest('Email${Const.OTPEMAIL_PASSWORD}');

      SharedStoreUtils.setValue(Const.OTP, myOTP);
      Navigator.pushReplacement(
          context,
          LeftOpenScreen(
              widget: OtpScreen(
                getPhone: ETphone.text.toString(),
                getEmail: ETemail.text.toString(),
                country: country,
                skipIntro: widget.skipIntro,
                popLogin: widget.popLogin,
              )));

    setState(() {
      showProgress = false;
    });
  }

  @override
  void initState() {
    super.initState();
    pageController = PageController(
      initialPage: (widget.skipIntro == '0') ? 1 : 0,
      keepPage: true,
    );
    if (widget.country != '') {
      setState(() {
        country = widget.country;
        ETphone.text = widget.phone;

        _sendOTP();
      });
    }
    getSharedStore();
  }

  _sendOTP() async {
    setState(() {
      showProgress = true;
    });
    String mynum = ETphone.text;
    String myMsg;
    myMsg = "$myOTP is your OTP for ${Const.AppName} Application Login. Thanks for using ${Const.AppName}";

    // String whatsappUrl="https://whatsapp.myappstores.com/api/sendText?token="+Const.WHATSAPP_TOKEN+"&phone=$countryCodeint$mynum&message=$myMsg";
    String whatsappUrl = "https://smpplive.com/api/send_sms/single_sms?to=$contry_code$mynum&${Const.SMSGATEWAY}&content=$myMsg";

    final response = await http.get(Uri.parse(whatsappUrl.toString()), headers: {HttpHeaders.acceptHeader: Const.POSTHEADER});

    data = json.decode(response.body);
    setState(() {
      showProgress = false;
    });

    SharedStoreUtils.setValue(Const.OTP, myOTP);
    Navigator.pushReplacement(context, LeftOpenScreen(
        widget: OtpScreen(
          getPhone: ETphone.text.toString(),
          getEmail: ETemail.text.toString(),
          country: country,
          skipIntro: widget.skipIntro,
          popLogin: widget.popLogin,
        )));
  }

  @override
  void dispose() {
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    initRelativeScaler(context);
    _keyboardVisible = MediaQuery.of(context).viewInsets.bottom != 0;
    return MaterialApp(
      home: SafeArea(
        top: false,
        child: _screenBody(),
      ),
    );
  }

  _screenBody() {
    return Scaffold(
      body: Container(
        decoration: BoxDecoration(
          color: fc_bg2,
        ),
        width: MediaQuery.of(context).size.width,
        height: MediaQuery.of(context).size.height,
        child: PageView(
          scrollDirection: Axis.horizontal,
          physics: NeverScrollableScrollPhysics(),
          controller: pageController,
          children: [
            if (widget.skipIntro == false) welcomeWidget(),
            phoneWidget(),
          ],
        ),
      ),
    );
  }

  welcomeWidget() {
    return Container(
      child: Stack(
        children: [
          Positioned(
            top: 0,
            left: 0,
            right: 0,
            bottom: 0,
            child: Container(
              child: Image.asset(
                'assets/images/indflag.jpg',
                width: MediaQuery.of(context).size.width,
                height: MediaQuery.of(context).size.height,
                fit: BoxFit.cover,
              ),
            ),
          ),
          Positioned(
            top: 0,
            left: 0,
            right: 0,
            bottom: 0,
            child: Container(
              width: MediaQuery.of(context).size.width,
              height: MediaQuery.of(context).size.height,
              padding: EdgeInsets.fromLTRB(sy(15), sy(15), sy(15), sy(25)),
              child: Column(
                mainAxisAlignment: MainAxisAlignment.end,
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    'Welcome to'.toUpperCase(),
                    style: TextStyle(
                      color: fc_bg2,
                      fontSize: sy(l),
                      fontWeight: FontWeight.w600,
                    ),
                  ),
                  SizedBox(
                    height: sy(3),
                  ),
                  Text(
                    Const.AppName.toUpperCase(),
                    style: TextStyle(
                      color: fc_bg2,
                      fontSize: sy(l),
                      fontWeight: FontWeight.w600,
                    ),
                  ),
                  SizedBox(
                    height: sy(5),
                  ),
                  Text(
                    'Where Quality and ethics matter',
                    style: TextStyle(
                      color: fc_5,
                      fontSize: sy(xs),
                      fontWeight: FontWeight.w600,
                    ),
                  ),
                  SizedBox(
                    height: sy(10),
                  ),
                  ElevatedButton(
                    onPressed: () {
                      _changePage(
                          int.parse(pageController.page!.toStringAsFixed(0)) +
                              1);
                    },
                    style: elevatedButton(ThemePrimary, sy(5)),
                    child: Container(
                      width: MediaQuery.of(context).size.width * 0.35,
                      alignment: Alignment.center,
                      padding: EdgeInsets.fromLTRB(sy(5), sy(8), sy(5), sy(8)),
                      child: Row(
                        mainAxisAlignment: MainAxisAlignment.center,
                        crossAxisAlignment: CrossAxisAlignment.center,
                        children: [
                          Text(
                            "Let's Start".toUpperCase(),
                            style: TextStyle(
                              color: fc_bg,
                              fontSize: sy(n-1),
                              fontWeight: FontWeight.w600,
                            ),
                          ),
                          SizedBox(
                            width: sy(2),
                          ),

                          Icon(
                            Icons.double_arrow,
                            color: fc_bg,
                            size: sy(l),
                          )
                        ],
                      ),
                    ),
                  ),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }

  phoneWidget() {
    return Container(
      width: MediaQuery.of(context).size.width,
      height: MediaQuery.of(context).size.height,
      color: fc_bg,
      child: SingleChildScrollView(
        child: Container(
          width: MediaQuery.of(context).size.width,
          height: MediaQuery.of(context).size.height,
          padding: EdgeInsets.fromLTRB(sy(0), sy(20), sy(0), sy(0)),
          child: Column(
            mainAxisAlignment: MainAxisAlignment.start,
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Container(
                width: MediaQuery.of(context).size.width,
                padding: EdgeInsets.fromLTRB(sy(15), sy(15), sy(15), sy(15)),
                child: Row(
                  mainAxisAlignment: MainAxisAlignment.start,
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Expanded(
                      child: Column(
                        mainAxisAlignment: MainAxisAlignment.start,
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            'Login Account',
                            style: TextStyle(
                              color: fc_2,
                              fontSize: sy(l),
                              fontWeight: FontWeight.w700,
                            ),
                          ),

                          Text(
                            'Provide valid credentials to login',
                            style: TextStyle(
                              color: fc_2,
                              fontSize: sy(s-1),
                              fontWeight: FontWeight.w400,
                            ),
                          )
                        ],
                      ),
                    ),
                    // Container(
                    //   padding: EdgeInsets.fromLTRB(sy(0), sy(5), sy(0), sy(0)),
                    //   child: GestureDetector(
                    //     child: Image.asset(
                    //       'assets/images/flag/in.png',
                    //       width: sy(18),
                    //       height: sy(12),
                    //       fit: BoxFit.contain,
                    //     ),
                    //     behavior: HitTestBehavior.translucent,
                    //     onTap: () {
                    //       //_popCountry();
                    //     },
                    //   ),
                    // ),
                  ],
                ),
              ),
              Container(
                margin: EdgeInsets.fromLTRB(sy(5), sy(5), sy(5), sy(5)),
                padding: EdgeInsets.fromLTRB(sy(5), sy(5), sy(5), sy(5)),
                decoration: BoxDecoration(
                  color: Colors.grey.shade300,
                  borderRadius: BorderRadius.circular(sy(30)),
                ),
                child: Row(
                  mainAxisAlignment: MainAxisAlignment.start,
                  crossAxisAlignment: CrossAxisAlignment.end,
                  children: [
                    Expanded(
                      child: GestureDetector(
                        behavior: HitTestBehavior.translucent,
                        onTap: () {
                          setState(() {
                            email = true;
                            errMsg = '';
                          });
                        },
                        child: Container(
                          decoration: BoxDecoration(
                            color: (email == true)
                                ? Colors.white
                                : Colors.grey.shade300,
                            borderRadius: BorderRadius.circular(sy(10)),
                          ),
                          padding:
                              EdgeInsets.fromLTRB(sy(0), sy(6), sy(0), sy(6)),
                          alignment: Alignment.center,
                          child: Text(
                            'Email',
                            style: TextStyle(
                              color: (email == true) ? fc_2 : fc_3,
                              fontWeight: (email == true)
                                  ? FontWeight.w700
                                  : FontWeight.w400,
                              fontSize: sy(n - 1),
                            ),
                          ),
                        ),
                      ),
                    ),
                    Expanded(
                      child: GestureDetector(
                        behavior: HitTestBehavior.translucent,
                        onTap: () {
                          setState(() {
                            email = false;
                            errMsg = '';
                          });
                        },
                        child: Container(
                          decoration: BoxDecoration(
                            color: (email == false)
                                ? Colors.white
                                : Colors.grey.shade300,
                            borderRadius: BorderRadius.circular(sy(10)),
                          ),
                          padding:
                              EdgeInsets.fromLTRB(sy(0), sy(6), sy(0), sy(6)),
                          alignment: Alignment.center,
                          child: Text(
                            'Phone Number',
                            style: TextStyle(
                              color: (email == false) ? fc_2 : fc_3,
                              fontWeight: (email == false)
                                  ? FontWeight.w700
                                  : FontWeight.w400,
                              fontSize: sy(n - 1),
                            ),
                          ),
                        ),
                      ),
                    ),
                  ],
                ),
              ),
              SizedBox(
                height: sy(10),
              ),
              Container(
                width: MediaQuery.of(context).size.width,
                alignment: Alignment.center,
                margin: EdgeInsets.fromLTRB(sy(5), sy(5), sy(5), sy(5)),
                child: Image.asset(
                  'assets/images/logo.jpg',
                  height: sy(70),
                ),
              ),
              SizedBox(
                height: sy(5),
              ),
              Container(
                padding: EdgeInsets.fromLTRB(sy(15), sy(5), sy(15), sy(5)),
                child: Column(
                  children: [
                    Row(
                      children: [
                        Expanded(
                          child: Text(
                            (email == false) ? 'Phone number' : 'Email',
                            style: TextStyle(
                              color: fc_3,
                              fontWeight: FontWeight.w500,
                              fontSize: sy(n),
                            ),
                          ),
                        )
                      ],
                    ),
                    SizedBox(
                      height: sy(5),
                    ),
                    Container(
                      decoration: BoxDecoration(
                        color: Colors.grey.shade300,
                        borderRadius: BorderRadius.circular(sy(5)),
                        border:
                            Border.all(color: Colors.grey.shade300, width: 1),
                      ),
                      padding:
                          EdgeInsets.fromLTRB(sy(8), sy(0), sy(8), sy(0)),
                      child: (email == false)
                          ? Row(
                              children: [
                                GestureDetector(
                                  child: Container(
                                    child: Row(
                                      children: [
                                        Image.asset(
                                          'assets/images/flag/' +
                                              WidgetHelp.getNameFromId(
                                                      country,
                                                      World.Countries,
                                                      'phonecode',
                                                      'sortname')
                                                  .toString()
                                                  .toLowerCase() +
                                              '.png'.toString(),
                                          width: sy(12),
                                          height: sy(12),
                                          fit: BoxFit.contain,
                                        ),
                                        SizedBox(
                                          width: sy(3),
                                        ),
                                        Text(
                                          country,
                                          style: TextStyle(
                                            color: fc_4,
                                            fontWeight: FontWeight.w600,
                                            fontSize: sy(n),
                                          ),
                                        ),
                                      ],
                                    ),
                                  ),
                                  behavior: HitTestBehavior.translucent,
                                  onTap: () {
                                    //_popCountry();
                                  },
                                ),
                                SizedBox(
                                  width: sy(5),
                                ),
                                Expanded(
                                  child: TextField(
                                    controller: ETphone,
                                    keyboardType: TextInputType.phone,
                                    textAlign: TextAlign.left,
                                    inputFormatters: [
                                      LengthLimitingTextInputFormatter(10),
                                    ],
                                    decoration: InputDecoration(
                                        // counter: Offstage(),
                                        hintText: 'Enter your phone number',
                                        hintStyle: TextStyle(
                                            color: fc_5, fontSize: sy(n-1), fontWeight: FontWeight.w400),
                                        focusedBorder: InputBorder.none,
                                        enabledBorder: InputBorder.none,
                                        border: InputBorder.none,
                                        isDense: false),
                                    style:
                                        TextStyle(color: fc_2, fontSize: sy(n)),
                                    textInputAction: TextInputAction.done,
                                    onChanged: (val) {
                                      setState(() {
                                        if (val.length > 8) {
                                          validPhone = true;
                                        } else {
                                          validPhone = false;
                                        }
                                        validEmail = false;
                                      });
                                    },
                                    autofocus: false,
                                  ),
                                ),
                                if (validPhone == true)
                                  Icon(
                                    Icons.check_circle,
                                    color: Colors.green,
                                    size: sy(l),
                                  ),
                              ],
                            )
                          : Row(
                              mainAxisAlignment: MainAxisAlignment.start,
                              crossAxisAlignment: CrossAxisAlignment.center,
                              children: [
                                Icon(
                                  Icons.email_outlined,
                                  color: Colors.grey.shade700,
                                  size: sy(l),
                                ),
                                SizedBox(
                                  width: sy(5),
                                ),
                                Expanded(
                                  child: TextField(
                                    controller: ETemail,
                                    keyboardType: TextInputType.emailAddress,
                                    textAlign: TextAlign.left,
                                    decoration: InputDecoration(
                                        // counter: Offstage(),
                                        hintText: 'Enter your email',
                                        hintStyle: TextStyle(
                                            color: fc_5, fontSize: sy(n-1),fontWeight: FontWeight.w400),
                                        focusedBorder: InputBorder.none,
                                        enabledBorder: InputBorder.none,
                                        border: InputBorder.none,
                                        isDense: false),
                                    style:
                                        TextStyle(color: fc_2, fontSize: sy(n)),
                                    textInputAction: TextInputAction.done,
                                    onChanged: (val) {
                                      setState(() {
                                        bool emailValid = RegExp(
                                                r"^[a-zA-Z0-9.a-zA-Z0-9.!#$%&'*+-/=?^_`{|}~]+@[a-zA-Z0-9]+\.[a-zA-Z]+")
                                            .hasMatch(ETemail.text);
                                        if (emailValid == true) {
                                          validEmail = true;
                                        } else {
                                          validEmail = false;
                                        }
                                        validPhone = false;
                                        errMsg = '';
                                      });
                                    },
                                    autofocus: false,
                                  ),
                                ),
                                if (validEmail == true)
                                  Icon(
                                    Icons.check_circle,
                                    color: Colors.green,
                                    size: sy(l),
                                  ),
                              ],
                            ),
                    ),
                    SizedBox(
                      height: sy(5),
                    ),
                    if (errMsg != '')
                      Text(
                        errMsg,
                        style: TextStyle(
                          color: Colors.red.shade400,
                        ),
                      ),
                    SizedBox(
                      height: sy(15),
                    ),
                    ElevatedButton(
                        style: elevatedButton(ThemePrimary, sy(50)),
                        onPressed: () {
                          errMsg = '';
                          widget.skipIntro = false;
                          if (email == false) {
                            if (ETphone.text.length >= 9) {
                              _openOTPScreen();
                            } else {
                              Pop.errorTop(
                                  context, 'Please Enter phone number');
                            }
                          } else {
                            bool emailValid = RegExp(
                                    r"^[a-zA-Z0-9.a-zA-Z0-9.!#$%&'*+-/=?^_`{|}~]+@[a-zA-Z0-9]+\.[a-zA-Z]+")
                                .hasMatch(ETemail.text);
                            if (ETemail.text != '' && emailValid == true) {
                              _checkEmail();
                            } else {
                              Pop.errorTop(
                                  context, 'Enter valid email address');
                            }
                          }
                        },
                        child: ProgressButton(
                          showProgress: showProgress,
                          bttxtcolor: fc_bg,
                          bttext: 'Send OTP',
                          btwidth: Width(context),
                          btheight: sy(32),
                        ))
                  ],
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  _openOTPScreen() {
    if(ETphone.text != '') {
      SharedStoreUtils.setValue(Const.OTP, myOTP);
      SharedStoreUtils.setValue(Const.COUNTRY, country);
      Provider.of<AppSetting>(context, listen: false).country = country;
      Navigator.pushReplacement(context, LeftOpenScreen(
          widget: OtpScreen(
            getPhone: ETphone.text.toString(),
            getEmail: ETemail.text.toString(),
            country: country,
            skipIntro: widget.skipIntro,
            popLogin: widget.popLogin,
          )));
    } else {
      Pop.successTop(
          context,
          'Enter phone number \nPhone number field should not empty',);
    }
  }

  _changePage(int page) {
    setState(() {
      pageController.animateToPage(
        page,
        duration: Duration(milliseconds: 800),
        curve: Curves.linear,
      );
    });
  }

  Future _popCountry() async {
    Map results = Map();
    try {
      results =
          await Navigator.push(context, OpenScreen(widget: CountryScreen()));
      if (results != null) {
        setState(() {
          country = results['id'].toString();
          contry_code = results['id'].toString();

          SharedStoreUtils.setValue(Const.COUNTRY, country);
          Provider.of<AppSetting>(context, listen: false).country = country;
        });
      }
    } catch (e) {
      print('cancel');
    }
  }

}
