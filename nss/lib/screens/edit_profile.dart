import 'dart:convert';
import 'dart:io';
import 'dart:math';
import 'package:flutter/cupertino.dart' as cup;
import 'package:flutter/cupertino.dart';
import 'package:flutter/material.dart';
import 'package:image_cropper/image_cropper.dart';
import 'package:image_picker/image_picker.dart';
import 'package:mailer/mailer.dart';
import 'package:mailer/smtp_server.dart';
import 'package:nss/components/mytext.dart';
import 'package:nss/components/widget_help.dart';
import 'package:nss/library/otp_field.dart';
import 'package:nss/screens/splash_screen.dart';
import 'package:nss/utils/const.dart';
import 'package:nss/utils/urls.dart';
import 'package:provider/provider.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:http/http.dart' as http;
import 'package:async/async.dart';
import 'package:path/path.dart' as path;
import 'package:location/location.dart' as loc;
import '../../components/custom_bottomsheet.dart';
import '../components/pop.dart';
import '../components/progress_button.dart';
import '../components/progress_button_jump.dart';
import '../components/relative_scale.dart';
import '../library/country.dart';
import '../model/save_user_data.dart';
import '../router/open_screen.dart';
import '../utils/app_settings.dart';
import '../utils/shared_preferences.dart';
import '../utils/style_sheet.dart';

class EditProfile extends StatefulWidget {
  EditProfile({Key? key}) : super(key: key);

  @override
  State<EditProfile> createState() => _EditProfileState();
}

class _EditProfileState extends State<EditProfile> with RelativeScale {
  final _scaffoldKey = GlobalKey<ScaffoldState>();

  bool showProgress = false;
  Map data = Map();
  List userDetail = [];
  String UserId = '';
  String exp ='';
  String phone = '';
  String email = '';
  String profile = '';
  String name = '';
  String expert = '';

  TextEditingController ETname = TextEditingController();
  TextEditingController ETemail = TextEditingController();
  TextEditingController ETphone = TextEditingController();
  TextEditingController ETexp = TextEditingController();
  TextEditingController ETyear = TextEditingController();


  String getOTP = '';
  String emailver = '0';

  String myOTP = '2003';
  bool showOTP = false;
  String gender='male';
  String expertLevel='1';
  bool _keyboardVisible = false;



  @override
  void initState() {
    super.initState();
    getSharedStore();
  }

  @override
  void dispose() {
    super.dispose();
  }

  getSharedStore() async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    setState(() {
      UserId = prefs.getString(Const.SID) ?? '0';
      UserId = Provider.of<AppSetting>(context, listen: false).sid;
      phone = Provider.of<AppSetting>(context, listen: false).phone;
      exp = Provider.of<AppSetting>(context, listen: false).exp;
      email = Provider.of<AppSetting>(context, listen: false).email;
      profile = Provider.of<AppSetting>(context, listen: false).profile;
      name = Provider.of<AppSetting>(context, listen: false).name;
      gender = Provider.of<AppSetting>(context, listen: false).gender;
      expert = Provider.of<AppSetting>(context, listen: false).expert;

      ETemail.text = Provider.of<AppSetting>(context, listen: false).email;
      ETname.text = Provider.of<AppSetting>(context, listen: false).name;
      ETphone.text = Provider.of<AppSetting>(context, listen: false).phone;
      ETexp.text = Provider.of<AppSetting>(context, listen: false).expert;
      ETyear.text = Provider.of<AppSetting>(context, listen: false).exp;


    });
  }

  updateUser() async {
    if (ETname.text == '' || ETemail.text == ''   ) {
      Pop.errorTop(
        context,
          "Please fill all details" );
    } else {
      setState(() {
        showProgress = true;
      });

      var body = {
        "key": Const.APPKEY,
        "uid": UserId,
        "name": ETname.text,
        "email": ETemail.text,
        "expert": ETexp.text,
        "exp": ETyear.text,
        "gender": gender.toString(),

      };
      apiTest(body.toString());
      final response = await http.post(Uri.parse(Urls.UpdateProfile),
          headers: {HttpHeaders.acceptHeader: Const.POSTHEADER}, body: body);
      apiTest(response.request.toString());
      apiTest(body.toString());
      data = json.decode(response.body);

      apiTest(data.toString());
      setState(() {
        showProgress = false;
        showOTP = false;
        if(data['email']==true){
          userDetail=data['user'];
          SaveUserData(context, userDetail);

          Pop.successTop(context, "Updated. Please restart app to apply changes" );

        }else{

          Pop.errorTop(context, " Email already exists ");

        }

      });

    }
  }

/*  _sendEMail() async {
    setState(() {
      showProgress = true;
    });

    final smtpServer = gmail(Const.OTPEMAIL, Const.OTPEMAIL_PASSWORD);
    final equivalentMessage = Message()
      ..from = Address(Const.OTPEMAIL, Lang("Trends Research & Advisory ",  "اتجاهات البحوث والاستشارات" ))
      ..recipients.add(Address(ETemail.text.toString()))
      ..subject = '${myOTP} is your OTP'
      ..text = '${Const.AppName}\n${myOTP} is your OTP for Login'
      ..html =
          "<h1>${Const.AppName}</h1>\n<p>${myOTP} is your OTP for Login</p>";

    try {
      final sendReport = await send(equivalentMessage, smtpServer);
      setState(() {
        showOTP = true;
      });
    } on MailerException catch (e) {
      debugPrint(e.toString());
    }

    setState(() {
      showProgress = false;
    });
  }*/

  @override
  Widget build(BuildContext context) {
    initRelativeScaler(context);
    _keyboardVisible = MediaQuery.of(context).viewInsets.bottom != 0;
    return Container(
        color: fc_bg,
        child: SafeArea(
          child: Scaffold(
            key: _scaffoldKey,
            //   resizeToAvoidBottomPadding: false,
            body: Container(
              color: fc_bg,
              height: Height(context),
              width: Width(context),
              child: Stack(
                children: <Widget>[
                  Positioned(
                    top: 0,
                    left: 0,
                    right: 0,
                    bottom: 0,
                    child: _screenBody(),
                  ),

                  // MyProgressBar(showProgress),
                ],
              ),
            ),
            appBar: AppBar(
              backgroundColor: fc_bg,
              titleSpacing: 0,
              elevation: 0,
              centerTitle: true,
              title: Row(
                children: [
                  Text('PROFILE'.toUpperCase(),
                    style: TextStyle(
                      color: ThemePrimary,
                      fontSize: sy(n+1),
                      fontWeight: FontWeight.bold,
                    ),),
                ],
              ),

              leading:  IconButton(onPressed: (){
                Navigator.of(context).pop(true);
              }, icon: Icon(Icons.arrow_back,size: sy(l),color: fc_2,)),

            ),
          ),
        ));
  }

  _screenBody() {
    return Container(
      child: Column(
        children: [
          Expanded(child: SingleChildScrollView(
            scrollDirection: Axis.vertical,
            child: Container(
              width: Width(context),
              color: fc_bg,
              //  padding: EdgeInsets.fromLTRB(sy(15), sy(0), sy(15), sy(0)),
              margin: EdgeInsets.fromLTRB(sy(15), sy(5), sy(15), sy(15)),
              child: Column(
                mainAxisAlignment: MainAxisAlignment.start,
                crossAxisAlignment: CrossAxisAlignment.start,
                children: <Widget>[

                  Column(
                    mainAxisAlignment: MainAxisAlignment.start,
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [

                      //First Name
                      labelText(" First Name "),
                      Container(
                        decoration: decoration_border(fc_textfield_bg, fc_textfield_bg, sy(1), sy(5), sy(5), sy(5), sy(5)),
                        //height: sy(25),
                        padding: EdgeInsets.fromLTRB(sy(10), sy(0), sy(3), sy(0)),
                        alignment: Alignment.center,
                        child: TextField(
                          controller: ETname,
                          keyboardType: TextInputType.name,
                          textCapitalization: TextCapitalization.sentences,
                          textAlign: TextAlign.left,
                          decoration: InputDecoration(
                            isDense: true,
                            hintText: " Your Name ",
                            hintStyle: ts_Regular(sy(n-1), fc_4),
                            focusedBorder: InputBorder.none,
                            enabledBorder: InputBorder.none,
                            border: InputBorder.none,
                          ),
                          style: ts_Regular(sy(n-1), fc_2),
                          textInputAction: TextInputAction.next,
                          autofocus: false,
                        ),
                      ),

                      labelText(" Expert in Teaching "),
                      Container(
                        decoration: decoration_border(fc_textfield_bg, fc_textfield_bg, sy(1), sy(5), sy(5), sy(5), sy(5)),
                        //height: sy(25),
                        padding: EdgeInsets.fromLTRB(sy(10), sy(0), sy(3), sy(0)),
                        alignment: Alignment.center,
                        child: TextField(
                          controller: ETexp,
                          keyboardType: TextInputType.name,
                          textCapitalization: TextCapitalization.sentences,
                          textAlign: TextAlign.left,
                          decoration: InputDecoration(
                            isDense: true,
                            hintText: " Your Register Number ",
                            hintStyle: ts_Regular(sy(n-1), fc_4),
                            focusedBorder: InputBorder.none,
                            enabledBorder: InputBorder.none,
                            border: InputBorder.none,
                          ),
                          style: ts_Regular(sy(n-1), fc_2),
                          textInputAction: TextInputAction.next,
                          autofocus: false,
                        ),
                      ),


                      //Email
                      labelText("Email Address  "),
                      Container(
                        //  height: sy(25),
                        child: Row(
                          children: [
                            Expanded(
                              child:Container(
                                decoration: decoration_border(fc_textfield_bg, fc_textfield_bg,
                                    sy(1), sy(5), sy(5), sy(5), sy(5)),
                                padding: EdgeInsets.fromLTRB(sy(10), sy(0), sy(3), sy(0)),
                                alignment: Alignment.center,
                                child: TextField(
                                  controller: ETemail,
                                  keyboardType: TextInputType.emailAddress,
                                  textAlign: TextAlign.left,
                                  decoration: InputDecoration(
                                    isDense: true,
                                    hintText: 'Your Email',
                                    hintStyle: ts_Regular(sy(n-1), fc_4),
                                    focusedBorder: InputBorder.none,
                                    enabledBorder: InputBorder.none,
                                    border: InputBorder.none,
                                  ),
                                  style: ts_Regular(sy(n-1), fc_2),
                                  textInputAction: TextInputAction.next,
                                  autofocus: false,
                                  onChanged: (val){
                                    setState(() {
                                      if(email==val){
                                        emailver='1';
                                      }else{
                                        emailver='0';
                                      }
                                    });
                                  },
                                ),
                              ),
                            ),
                            SizedBox(width: sy(8),),
                            if (emailver == '1') verified(),
                            if (emailver == '0')ProgressButtonJump(
                                showProgress: showProgress,
                                bttext: "Verify",
                                btwidth: sy(45),
                                btheight: sy(18),
                                btround: sy(3),
                                bttextsize: sy(s - 2),
                                onTap: () {
                                  bool emailValid = RegExp(r"^[a-zA-Z0-9.a-zA-Z0-9.!#$%&'*+-/=?^_`{|}~]+@[a-zA-Z0-9]+\.[a-zA-Z]+").hasMatch(ETemail.text);
                                  if (ETemail.text != '' && emailValid==true){
                                    Pop.successTop(context, "Valid Email Address" );
                                    setState(() {
                                        showOTP = true;
                                    });
                                  }else{
                                    Pop.errorTop(context, 'Enter valid email address');
                                  }

                                }),
                          ],
                        ),
                      ),

                      //Experience
                      labelText(" Experience "),
                      Container(
                        decoration: decoration_border(fc_textfield_bg, fc_textfield_bg,
                            sy(1), sy(5), sy(5), sy(5), sy(5)),
                        padding: EdgeInsets.fromLTRB(sy(5), sy(0), sy(5), sy(0)),
                        child: Row(
                          children: [
                            SizedBox(
                              width: sy(3),
                            ),
                            Expanded(
                                child: TextField(
                                  controller: ETyear,
                                  keyboardType: TextInputType.phone,
                                  enabled: false,
                                  textAlign: TextAlign.left,
                                  decoration: InputDecoration(
                                      contentPadding:
                                      EdgeInsets.fromLTRB(sy(0), sy(5), sy(0), sy(5)),
                                      border: InputBorder.none,
                                      isDense: true),
                                  style: ts_Regular(sy(n-1), fc_2),
                                )),
                          ],
                        ),
                      ),

                      //Mobile
                      labelText(" Mobile Number "),
                      Container(
                        decoration: decoration_border(fc_textfield_bg, fc_textfield_bg,
                            sy(1), sy(5), sy(5), sy(5), sy(5)),
                        padding: EdgeInsets.fromLTRB(sy(5), sy(0), sy(5), sy(0)),
                        child: Row(
                          children: [
                            SizedBox(
                              width: sy(3),
                            ),
                            Expanded(
                                child: TextField(
                                  controller: ETphone,
                                  keyboardType: TextInputType.phone,
                                  enabled: false,
                                  textAlign: TextAlign.left,
                                  decoration: InputDecoration(
                                      contentPadding:
                                      EdgeInsets.fromLTRB(sy(0), sy(5), sy(0), sy(5)),
                                      border: InputBorder.none,
                                      isDense: true),
                                  style: ts_Regular(sy(n-1), fc_2),
                                )),
                          ],
                        ),
                      ),

                      //Gender
                      labelText(" Gender "),
                      GestureDetector(
                        behavior: HitTestBehavior.translucent,
                        onTap: () {
                          CustomBottomSheet(context: context, title: 'Gender',
                              child:Column(
                                children: [
                                  for(int i=0;i<Const.gender.length;i++)
                                    ListTile(
                                        title: Mytext(Const.gender[i]['label$cur_Lang'], fc_2),
                                        trailing :(gender== Const.gender[i]['id'])?Icon(Icons.check,color: fc_2,):null,
                                        onTap: () {
                                          setState(() {
                                            gender = Const.gender[i]['id'];
                                            Navigator.of(context).pop();
                                          });
                                        }),
                                ],
                              ), sy: sy,height: 0.4);
                        },
                        child: Container(
                          decoration: decoration_border(fc_textfield_bg, fc_textfield_bg,
                              sy(1), sy(5), sy(5), sy(5), sy(5)),
                          padding: EdgeInsets.fromLTRB(sy(10), sy(5), sy(5), sy(5)),
                          alignment: Alignment.centerLeft,
                          child:  Mytext((gender == '') ? 'Select gender' : WidgetHelp.getNameFromId(gender,Const.gender,'id','label'), (gender == '')?fc_5:fc_1,size: sy(n-1),
                          ),
                        ),
                      ),
                    ],
                  ),

                  // SizedBox(height: sy(30),),
                  // Center(
                  //   child: GestureDetector(
                  //     onTap: () {
                  //       Pop.messagePop(context, 'Delete Account', 'Are you sure want to delete your account? Our team will contact you regarding this. After verification only your profile will get deleted completely', Icons.restore_from_trash_sharp , [
                  //         GestureDetector(
                  //           onTap: () {
                  //             _clearSharedpreference();
                  //           },
                  //           child: Container(
                  //               child:Mytext(Lang( 'Delete', 'يمسح'),Colors.white,size: sy(s+1),)
                  //           ),
                  //         ),
                  //       ]);
                  //     },
                  //     child: Container(
                  //         width: Width(context)*0.6,
                  //         height: sy(30),
                  //         alignment: Alignment.center,
                  //         child:Mytext(Lang( 'Delete Account', 'حذف الحساب'),Colors.red,size: sy(s+1),)
                  //     ),
                  //   ),
                  // ),
                  // SizedBox(height: sy(25),),
                ],
              ),
            ),
          )),
          if(_keyboardVisible==false) Container(
           color: ThemePrimary,
           child:  ElevatedButton(
             style: elevatedButton(ThemePrimary, sy(0)),
             onPressed: (){
               if(ETname.text==''){
                 Pop.errorTop(context, 'Enter name');
               }else{
                 bool emailValid = RegExp(r"^[a-zA-Z0-9.a-zA-Z0-9.!#$%&'*+-/=?^_`{|}~]+@[a-zA-Z0-9]+\.[a-zA-Z]+").hasMatch(ETemail.text);
                 if(ETemail.text=='' && emailValid==true){
                   Pop.errorTop(context, 'Enter valid email');
                 }else{
                   updateUser();
                 }
               }

             },
             child: Container(
               width: Width(context),
               child: ProgressButton(
                 btpadding: sy(5),
                 bttext: " Save Changes ",
                 btwidth: Width(context) ,
                 showProgress: showProgress,
                 btheight: sy(30),
               ),
             ),
           ),
         )
        ],
      ),
    );
  }

  labelText(String label) {
    return Container(
      padding: EdgeInsets.fromLTRB(sy(0), sy(13), sy(0), sy(5)),
      child: Mytext(
        label,fc_3,size: sy(s),
      ),
    );
  }

  verified() {
    return Container(
      child: Row(
        children: [
          Icon(
            Icons.check_circle,
            size: sy(s),
            color: Colors.green,
          ),
          SizedBox(
            width: sy(2),
          ),
          Text(
              " Verified ",
            style: ts_Regular(sy(s - 2), Colors.green),
          ),
        ],
      ),
    );
  }

  // otpWidget() {
  //   return Container(
  //     decoration: decoration_round(fc_bg2, sy(20), sy(20), sy(20), sy(20)),
  //     // height: sy(200),
  //     padding: EdgeInsets.fromLTRB(sy(10), sy(15), sy(10), sy(20)),
  //     margin: EdgeInsets.fromLTRB(sy(0), sy(10), sy(0), sy(10)),
  //     width: Width(context),
  //     child: Column(
  //       children: [
  //         Mytext(
  //           Lang("Enter Your OTP", "أدخل OTP الخاص بك"),fc_1,size: sy(l),
  //         ),
  //         Divider(),
  //         Mytext(
  //           Lang(" OTP sent to your email address. ", " تم إرسال OTP إلى عنوان بريدك الإلكتروني. "),fc_2
  //         ),
  //         SizedBox(
  //           height: sy(20),
  //         ),
  //         Container(
  //           height: Width(context) * 0.15,
  //           child: OTPTextField(
  //             length: 4,
  //             width: Width(context),
  //             fieldWidth: Width(context) * 0.125,
  //             style: ts_Regular(sy(l), fc_1),
  //             textFieldAlignment: MainAxisAlignment.spaceAround,
  //             fieldStyle: FieldStyle.box,
  //             keyboardType: TextInputType.number,
  //             otpFieldStyle: OtpFieldStyle(
  //                 focusBorderColor: fc_bg,
  //                 enabledBorderColor: fc_bg,
  //                 disabledBorderColor: fc_bg,
  //                 backgroundColor: fc_bg,
  //                 borderColor: fc_bg),
  //             onCompleted: (pin) {
  //               setState(() {
  //                 getOTP = pin.toString();
  //                 if (myOTP == getOTP || getOTP == Const.OTP) {
  //                   emailver='1';
  //                   updateUser();
  //                 } else {
  //                   Pop.errorTop(
  //                       context, Lang("Wrong OTP. Enter correct one  ", " OTP خاطئ. أدخل الصحيح ") );
  //                 }
  //               });
  //             },
  //           ),
  //         ),
  //       ],
  //     ),
  //   );
  // }

  _clearSharedpreference() async {
    Provider.of<AppSetting>(context, listen: false).sid = "0";
    Provider.of<AppSetting>(context, listen: false).name = "";
    Provider.of<AppSetting>(context, listen: false).phone = "";
    Provider.of<AppSetting>(context, listen: false).email = "";
    Provider.of<AppSetting>(context, listen: false).emailVerify = "0";
    Provider.of<AppSetting>(context, listen: false).profile = "";
    Provider.of<AppSetting>(context, listen: false).country = "";
    Provider.of<AppSetting>(context, listen: false).city = "";
    Provider.of<AppSetting>(context, listen: false).skipSignup = "0";
    SharedStoreUtils.clearValue();
    Navigator.pushAndRemoveUntil(
        context, OpenScreen(widget: SplashScreen()), ModalRoute.withName("/"));
  }
}
