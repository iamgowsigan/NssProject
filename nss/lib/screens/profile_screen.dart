import 'dart:convert';
import 'dart:io';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:flutter/cupertino.dart';
import 'package:flutter/material.dart';
import 'package:image_cropper/image_cropper.dart';
import 'package:image_picker/image_picker.dart';
import 'package:nss/screens/phone_number.dart';
import 'package:nss/screens/splash_screen.dart';
import 'package:provider/provider.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'dart:io';
import 'package:async/async.dart';
import 'package:flutter/material.dart';
import 'package:path/path.dart' as path;
import 'package:location/location.dart' as loc;
import '../../components/custom_bottomsheet.dart';
import '../../components/custom_dialog.dart';
import '../../components/imagefullscreen.dart';
import '../../router/transparant_screen.dart';
import '../components/bottom_navigation.dart';
import '../components/custom_image_viewer.dart';
import '../components/mytext.dart';
import '../components/relative_scale.dart';
import '../components/url_open.dart';
import '../components/widget_help.dart';
import '../library/country.dart';
import '../library/upload_image.dart';
import '../router/open_screen.dart';
import '../utils/app_settings.dart';
import '../utils/const.dart';
import '../utils/shared_preferences.dart';
import '../utils/style_sheet.dart';
import '../utils/urls.dart';
import 'edit_profile.dart';

class ProfileScreen extends StatefulWidget {
  ProfileScreen({Key? key}) : super(key: key);

  @override
  _ProfileScreenState createState() {
    return _ProfileScreenState();
  }
}

class _ProfileScreenState extends State<ProfileScreen> with RelativeScale {
  final _scaffoldKey = GlobalKey<ScaffoldState>();
  String UserId='';
  String name='';
  String email='';
  String phone='';
  String expert='';
  String gender='';
  bool showProgress = false;
  Map data = Map();
  int pageCount = 1;
  ScrollController _scrollController = ScrollController();
  CroppedFile? _image;
  var imagepath;
  TextEditingController ETphone = TextEditingController();

  String languageSelected = '';
  TextDirection selectedTextDirection = TextDirection.ltr;

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
      UserId = Provider.of<AppSetting>(context, listen: false).sid;
      expert = Provider.of<AppSetting>(context, listen: false).expert;
      gender = Provider.of<AppSetting>(context, listen: false).gender;
      //expert =  prefs.getString(Const.EXPERT) ?? '';
      name = Provider.of<AppSetting>(context, listen: false).name;
      email = Provider.of<AppSetting>(context, listen: false).email;
      phone = Provider.of<AppSetting>(context, listen: false).phone;
      languageSelected = prefs.getString(Const.SELECTEDLANGUGAE) ?? 'English';

    });
  }
  void uploadImage1(CroppedFile _image) async {
    setState(() {
      showProgress = true;
    });
    // open a byteStream
    var stream = http.ByteStream(DelegatingStream.typed(_image.openRead()));
    // get file length
    //var length = await _image.length();
    var request = http.MultipartRequest("POST", Uri.parse(Urls.UpdateImage));
    request.fields["key"] = Const.APPKEY;
    request.fields["uid"] = UserId;
    var multipartFile = http.MultipartFile('image', stream, 1,
        filename: path.basename(_image.path));
    request.files.add(multipartFile);

    await request.send().then((response) async {
      response.stream.transform(utf8.decoder).listen((value) {
        data = json.decode(value);
        if (data["success"] == true) {
          setState(() {
            showProgress = false;
            SharedStoreUtils.setValue(
                Const.PROFILE, data["filename"].toString());
            Provider.of<AppSetting>(context, listen: false).profile =
                data["filename"].toString();

            //profile = data["filename"].toString();
            debugPrint(data["filename"]);
          });
        }
        print(value);
      });
    }).catchError((e) {
      print(e);
    });
  }

  @override
  Widget build(BuildContext context) {
    // TODO: implement build
    UserId = Provider.of<AppSetting>(context, listen: false).sid;
    expert = Provider.of<AppSetting>(context, listen: false).expert;
    name = Provider.of<AppSetting>(context, listen: false).name;
    phone = Provider.of<AppSetting>(context, listen: false).phone;
    gender = Provider.of<AppSetting>(context, listen: false).gender;
    initRelativeScaler(context);
    return Container(
        color: fc_bg,
        child: SafeArea(
           top: false,
          child: Scaffold(
            key: _scaffoldKey,
            bottomNavigationBar: BottomNavigationWidget(ishome: false,order: 3,mcontext: context,controller: _scrollController,),
            body: Container(
              height: MediaQuery.of(context).size.height,
              width: MediaQuery.of(context).size.width,
              child: Stack(
                children: <Widget>[
                  Positioned(
                    top: 0,
                    left: 0,
                    right: 0,
                    bottom: 0,
                    child: _screenBody(),
                  ),
                ],
              ),
            ),
          ),
        )
    );
  }

  _screenBody() {
    return Container(
      color: fc_bg2,
      width: MediaQuery.of(context).size.width,
      child: Column(
        mainAxisAlignment: MainAxisAlignment.start,
        crossAxisAlignment: CrossAxisAlignment.start,
        children: <Widget>[
          topWidget(),
          Expanded(child: SingleChildScrollView(
            scrollDirection: Axis.vertical,
            child: Column(
              mainAxisAlignment: MainAxisAlignment.start,
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                if(IsEmpty(UserId)==false) cardTitle("ACCOUNT"),
                if(IsEmpty(UserId)==false) GestureDetector(
                  behavior: HitTestBehavior.translucent,
                  onTap: (){

                    Navigator.push(context, OpenScreen(widget: EditProfile()));
                  },
                  child: cardMenu("Profile Management", Icons.person, Color(
                      0xFF8D5F5F)),
                ),
                if(IsEmpty(UserId)==false) GestureDetector(
                  behavior: HitTestBehavior.translucent,
                  onTap: (){

                   smartCard();
                  },
                  child: cardMenu("Smart card", Icons.person, Color(
                      0xFF8D5F5F)),
                ),

                cardTitle("OTHERS "),
                GestureDetector(
                  behavior: HitTestBehavior.translucent,
                  onTap: (){
                    UrlOpenUtils.whatsapptoAdmin(_scaffoldKey, 'Hello ${Const.AppName}');
                  },
                  child: cardMenu(" Contact us ",FontAwesomeIcons.whatsapp, Color(0xFF8080FB)),
                ),
                GestureDetector(
                  behavior: HitTestBehavior.translucent,
                  onTap: (){
                    //Navigator.push(context, OpenScreen(widget: AboutScreen()));
                  },
                  child: cardMenu(" About us ", Icons.info, Color(0xFF56ABAB)),
                ),
                SizedBox(height: sy(30),),
                appInfo(),
              ],
            ),
          ))

        ],
      ),
    );
  }

  topWidget() {
    return Container(
      width: MediaQuery.of(context).size.width,
      decoration: BoxDecoration(
        color: ThemePrimary,
        borderRadius: BorderRadius.only(
          topLeft: Radius.circular(sy(0)),
          topRight: Radius.circular(sy(0)),
          bottomLeft: Radius.circular(sy(0)),
          bottomRight: Radius.circular(sy(0)),
        ),
        image: DecorationImage(
          image : AssetImage('assets/images/logo.jpg'),
          opacity: 0.15,
          fit: BoxFit.fill,
        )
      ),
      padding: EdgeInsets.fromLTRB(sy(15), MediaQuery.of(context).padding.top + sy(15), sy(10), sy(15)),
      child: Column(
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.start,
            crossAxisAlignment: CrossAxisAlignment.center,
            children: [
              GestureDetector(
                onTap: (){
                  Navigator.pop(context);
                },
                child: Container(
                    child: Icon(Icons.arrow_back,color: Colors.white,size: sy(xl),)),
              ),
              SizedBox(width: sy(5),),
              GestureDetector(
                child: Container(
                  width: sy(40),
                  height: sy(40),
                  child: Stack(
                    children: [
                      Positioned(child: Container(
                        padding: EdgeInsets.all(sy(2)),
                        decoration: decoration_round(fc_stock,sy(4),sy(4),sy(4),sy(4)),
                        child: (gender == 'male') ? Image.asset('assets/images/user11.jpg', width: sy(40), height: sy(40),) : Image.asset('assets/images/user16.jpg', width: sy(40), height: sy(40),),
                        //
                      ),top: 0,bottom: 0,left: 0,right: 0,),
                      if(IsEmpty(UserId)==false) Positioned(child: Icon(Icons.circle,color: Colors.green.withOpacity(0.8),size: sy(xs),),top: sy(3),right: sy(3),)
                    ],
                  )
                ),
                behavior: HitTestBehavior.translucent,
                onTap: (){
                  if(IsEmpty(UserId)==true){
                   //openSignup();
                  }else{
                    //openImage();
                  }
                },
              ),
              SizedBox(width: sy(8),),
              Expanded(child: GestureDetector(
                onTap: (){
                  if(IsEmpty(UserId)==true){
                    openSignup();
                  }
                },
                child: Column(
                  mainAxisAlignment: MainAxisAlignment.start,
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    // if(IsEmpty(UserId)==false)Mytext(UserId, ThemePrimary,bold: true, size: sy(s-1),),
                    Mytext( name.toString(), Colors.white,bold: true,letterspacing: 1.1,size: sy(n),),
                    SizedBox(height: sy(3),),
                    Row(
                      children: [
                        Mytext(WidgetHelp.greeting(), Colors.white, size: sy(s),),
                        Mytext( ', ', Colors.white, size: sy(s),),
                        Mytext("It's"+' '+WidgetHelp.getDay(), Colors.white, size: sy(s),),
                      ],
                    )
                  ],
                ),
              )),
              SizedBox(width: sy(8),),
              GestureDetector(
                onTap: (){
                  if(IsEmpty(UserId)==true){
                    openSignup();
                  }else{
                    CustomDialog(context: context,
                        title: 'Logout Confirmation?',
                        child:  Column(
                          children: [
                            //CustomeImageView(image: Urls.imageLocation+profile,width: sy(40),height: sy(40),radius: sy(5),fit: BoxFit.cover,),
                            SizedBox(height: 12,),
                            Mytext("Are you sure want to logout from your profile?",fc_3,size: sy(n-1),
                              textAlign: TextAlign.center,
                            ),
                            SizedBox(height: 12,),
                            ElevatedButton(onPressed: (){
                              Navigator.of(context).pop();
                              _clearSharedpreference();
                            },
                                style: elevatedButton(ThemePrimary, 5),
                                child:Container(
                                  width: Width(context),
                                  height:35,
                                  alignment: Alignment.center,
                                  child: Text( "Logout " ,style: ts_Regular(12, fc_bg),),
                                )),
                            TextButton(onPressed: (){

                              Navigator.of(context).pop(true);
                            },
                                // style: elevatedButton(TheamPrimary, sy(5)),
                                child:Container(
                                  width: Width(context),
                                  height: 30,
                                  alignment: Alignment.center,
                                  child: Text("Cancel ",style: ts_Regular(10, fc_2),),
                                )),
                          ],
                        ));
                  }


                },
                child: Icon((IsEmpty(UserId)==true)?Icons.login:Icons.logout_sharp,size: sy(l),color: Colors.white,),
              )

            ],
          ),
        ],
      ),
    );
  }

  cardTitle(String lable) {
    return Container(
      padding: EdgeInsets.fromLTRB(sy(15), sy(10), sy(10), sy(5)),
      child: Mytext(lable , fc_4,size: sy(s),bold: true,letterspacing: 1.3,),
    );
  }

  cardMenu(String lable,IconData icon, Color color,{String rigthTxt='',bool theme=false}) {
    return Container(
      width: Width(context),
      color: fc_bg,
      margin: EdgeInsets.fromLTRB(sy(2), sy(1), sy(2), sy(1)),
      padding: EdgeInsets.fromLTRB(sy(10), sy(5), sy(5), sy(5)),
      child: Row(
        children: [
          // Container(
          //   decoration: decoration_round(color, sy(5), sy(5), sy(5), sy(5)),
          //   padding: EdgeInsets.all(sy(4)),
          //   child: Icon(icon,size: sy(l),color: Colors.white,),
          //   alignment: Alignment.center,
          //
          // ),
          Container(
            decoration: decoration_round(Color(0xC5332C6F), sy(5), sy(5), sy(5), sy(5),),
            padding: EdgeInsets.all(sy(4)),
            child: Icon(icon,size: sy(l),color: Colors.white,),
            alignment: Alignment.center,

          ),
          SizedBox(width: sy(8),),
          Expanded(child: Mytext(lable , fc_3,size: sy(n-1),bold: false, ),),
          if(rigthTxt!='')Mytext(rigthTxt , fc_4,size: sy(s-1)  ),
          SizedBox(width: sy(5),),
          if(rigthTxt=='' && theme==false)Icon(Icons.arrow_forward_ios,size: sy(l),color: fc_4,),
          if(theme==true)Switch(
            onChanged: (val){
              setState(() {
                if(Provider.of<AppSetting>(context, listen: false).darkSelected==true){
                  setTheme('0');
                  Provider.of<AppSetting>(context, listen: false).changeTheme(ThemeMode.dark);
                }else{
                  setTheme('1');
                  Provider.of<AppSetting>(context, listen: false).changeTheme(ThemeMode.light);
                }
              });
              _scaffoldKey.currentState!.openEndDrawer();
            },
            value: Provider.of<AppSetting>(context, listen: false).darkSelected,
            activeColor: fc_icon,
            activeTrackColor: fc_4,
            inactiveThumbColor: fc_3,
            inactiveTrackColor: fc_4,
          ),
        ],
      ),
    );
  }

  appInfo() {
    return Container(
      width: Width(context),
      margin: EdgeInsets.fromLTRB(sy(2), sy(1), sy(2), sy(1)),
      padding: EdgeInsets.fromLTRB(sy(10), sy(5), sy(10), sy(5)),
      child: Column(
        children: [
          Mytext(Const.AppName , fc_4,size: sy(xs), ),
          Mytext(" All Rights Reserved - 2023 ", fc_4,size: sy(xs),  ),

        ],
      ),
    );
  }

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

  logoutConfirmation() {
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.all(Radius.circular(20.0))
        ),

        title: Mytext(" Logout? ",fc_1,
          textAlign: TextAlign.center,bold: true,
        ),
        content: IntrinsicWidth(
          child: IntrinsicHeight(
            child : Column(
              children: [

                Mytext("Are you sure want to logout from your profile?",fc_3,size: sy(s),
                  textAlign: TextAlign.center,
                ),
                SizedBox(height: 12,),
                ElevatedButton(onPressed: (){
                  Navigator.of(context).pop();

                  _clearSharedpreference();
                },
                    style: elevatedButton(ThemePrimary, 5),
                    child:Container(
                      width: Width(context),
                      height:35,
                      alignment: Alignment.center,
                      child: Text(" Logout ",style: ts_Regular(12, fc_bg),),
                    )),
                TextButton(onPressed: (){
                  Navigator.of(context).pop(true);
                },
                    // style: elevatedButton(TheamPrimary, sy(5)),
                    child:Container(
                      width: Width(context),
                      height: 30,
                      alignment: Alignment.center,
                      child: Text(" Cancel ", style: ts_Regular(10, fc_2),),
                    )),
              ],
            ),
          ),
        ),

      ),
    );
  }

  void setTheme(String val) {
    setState(() {
      try{
        SharedStoreUtils.setValue(Const.DARKMODE, val);
      }catch(e){
        print(e.toString());
      }

    });
  }

  // openImage(){
  //   Navigator.push(context, TransparantScreen(widget: UploadImage(url: Urls.UpdateImage, keys: ['uid'], values: [UserId] ))).then((results) {
  //     setState(() {
  //
  //       if(results['result'].toString()!='' && results['result'].toString()!='null'){
  //         //profile=results['result'].toString();
  //         Provider.of<AppSetting>(context, listen: false).profile=results['result'].toString();
  //         //apiTest(profile);
  //       }
  //
  //     });
  //   });
  //
  // }

  openSignup(){
    Navigator.push(context, TransparantScreen(widget: PhoneNumberScreen(popLogin: true,))).then((results) {
      setState(() {

      });
    });
  }

  smartCard() {
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.all(Radius.circular(20.0))
        ),
        backgroundColor: fc_bg,
        contentPadding: EdgeInsets.zero,
        content: IntrinsicWidth(
          child: IntrinsicHeight(
            child : Column(
              mainAxisAlignment: MainAxisAlignment.start,
              crossAxisAlignment: CrossAxisAlignment.center,
              children: [
                Container(
                  padding: EdgeInsets.fromLTRB(sy(10), sy(8), sy(10), sy(5)),
                  width: Width(context),
                  alignment: Alignment.centerLeft,
                  child:  Image.asset('assets/images/nss_logo.png', height: sy(40),fit: BoxFit.cover,),
                ),
                SizedBox(height: sy(15),),
                GestureDetector(
                  onTap: (){
                    setState(() {
                      // if(profile.toString()!=""){
                      //   Navigator.push(context, TransparantScreen(widget: ImageFullScreen(imgurl: Urls.imageLocation+profile.toString(),)));
                      // }
                    });
                  },
                  child: Container(
                    width: Width(context) * 0.6,
                    height: Width(context)* 0.6,
                    child:  Container(
                      width: Width(context),
                      height: Width(context),
                      decoration: decoration_round(ThemePrimary, sy(5), sy(5), sy(5), sy(5)),
                      padding: EdgeInsets.all(sy(5)),
                      child : Image.asset('assets/images/user0.jpg', width: Width(context), height: Height(context), fit: BoxFit.contain,),
                      //child: CustomeImageView(image: Urls.imageLocation+profile,width: Width(context),height: Height(context),fit: BoxFit.contain,placeholder: Urls.DummyImageBanner,),
                    ),
                  ),
                ),
                SizedBox(height: sy(15),),
                Mytext(name, fc_2,size: sy(l+1),bold: true,),
                SizedBox(height: sy(3),),
                Mytext('Staff Code : '+Const.APPCODE+'S0'+UserId, fc_2,size: sy(n-1),bold: false,),
                Container(width: Width(context)*0.6, height: 1,color: fc_5,margin: EdgeInsets.fromLTRB(0, sy(6), 0, sy(6)),),

              // if(phone.toString()!="")  Mytext('Phone : '+phone, fc_1,size: sy(s+1),),
              //   if(regNo.toString()!="")  Mytext('Reg No : '+regNo, fc_1,size: sy(s+1),),
                //SizedBox(height: sy(20),),
                Container(
                  padding: EdgeInsets.fromLTRB(sy(5), sy(5), sy(5), sy(10)),
                  child: Row(
                    children: [
                      Expanded(child: Column(
                        children: [
                          Mytext('Phone', fc_1,size: sy(s+1),),
                          SizedBox(height: sy(3),),
                          if(phone.toString()!="")  Mytext(phone, fc_2,size: sy(s),),
                        ],
                      )),


                      Expanded(child: Column(
                        children: [
                          Mytext('Domain', fc_1,size: sy(s+1),),
                          SizedBox(height: sy(3),),
                          if(expert.toString()!="")  Mytext(expert, fc_2,size: sy(s),),
                          //Mytext(WidgetHelp.getNameFromId(Provider.of<AppSetting>(context, listen: false).city, World.States, 'id', 'name'), fc_2,size: sy(s-1),),
                        ],
                      )),
                      // Expanded(child: Column(
                      //   children: [
                      //     Mytext('AGE', fc_1,size: sy(s),),
                      //     SizedBox(height: sy(3),),
                      //     Mytext(CustomeDate.age(Provider.of<AppSetting>(context, listen: false).dob), fc_2,size: sy(s-1),),
                      //   ],
                      // )),
                    ],
                  ),
                )
              ],
            ),
          ),
        ),

      ),
    );
  }

}