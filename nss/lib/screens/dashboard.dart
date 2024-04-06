import 'dart:convert';
import 'dart:io';

import 'package:flutter/material.dart';
import 'package:html_viewer_elite/html_viewer_elite.dart';
import 'package:nss/components/bottom_navigation.dart';
import 'package:nss/components/relative_scale.dart';
import 'package:nss/layout/cardBanner.dart';
import 'package:nss/model/save_user_data.dart';
import 'package:provider/provider.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:http/http.dart' as http;
import '../components/custom_image_viewer.dart';
import '../layout/cardBlood.dart';
import '../router/open_screen.dart';
import '../utils/app_settings.dart';
import '../utils/const.dart';
import '../utils/style_sheet.dart';
import '../utils/urls.dart';

class Dashboard extends StatefulWidget {
  Dashboard({Key? key}) : super(key: key);

  @override
  _DashboardState createState() {
    return _DashboardState();
  }
}

class _DashboardState extends State<Dashboard> with RelativeScale {
  final _scaffoldKey = GlobalKey<ScaffoldState>();
  String userId = '';
  String expert = '';
  String phone = '';
  String gender = '';
  String name = '';
  String exp = '';
  String profilePic = '';
  bool showProgress = false;
  List userDetail = [];
  List activityDetail = [];
  List bloodDetail = [];

  Map data = Map();
  ScrollController _scrollController = ScrollController();

  @override
  void initState() {
    super.initState();
    getSharedStore();
    //requestMultiplePermissions();
  }

  @override
  void dispose() {
    super.dispose();
  }

  getSharedStore() async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    setState(() {
      userId = prefs.getString(Const.SID) ?? '';
      exp = prefs.getString(Const.EXP) ?? '';
      userId = Provider.of<AppSetting>(context, listen: false).sid;
      expert = Provider.of<AppSetting>(context, listen: false).expert;
      phone = Provider.of<AppSetting>(context, listen: false).phone;

      name = Provider.of<AppSetting>(context, listen: false).name;
      //welcomeScreen = prefs.getString(Const.WELCOMESCREEN) ?? '0';
      _getServer();
    });
  }

  _getServer() async {
    setState(() {
      showProgress = true;
    });
    var body = {
      "key": Const.APPKEY,
      "uid": userId.toString(),
    };
    final response = await http.post(Uri.parse(Urls.Dashboard),
        headers: {HttpHeaders.acceptHeader: Const.POSTHEADER}, body: body);

    data = json.decode(response.body);

    setState(() {
      if (data["success"] == true) {
        userDetail = data["user"];
        activityDetail = data["activities"];
        bloodDetail = data["bloods"];

        // apiTest(userDetail.toString());
        // apiTest(bannerDetail.toString());
        // apiTest(bloodDetail.toString());

        SaveUserData(context, userDetail);
      }
    });
  }

  @override
  Widget build(BuildContext context) {
    userId = Provider.of<AppSetting>(context, listen: false).sid;
    profilePic = Provider.of<AppSetting>(context, listen: false).profile;
    gender = Provider.of<AppSetting>(context, listen: false).gender;
    initRelativeScaler(context);

    return MaterialApp(
        home: SafeArea(
      top: false,
      child: Scaffold(
        key: _scaffoldKey,
        bottomNavigationBar: BottomNavigationWidget(
            ishome: true,
            mcontext: context,
            controller: _scrollController,
            order: 1),
        body: Container(
          child: Stack(
            children: [
              Positioned(
                child: _screenBody(),
                top: 0,
                left: 0,
                right: 0,
                bottom: 0,
              ),
            ],
          ),
        ),
      ),
    ));
  }

  _screenBody() {
    return Container(
      width: MediaQuery.of(context).size.width,
      height: MediaQuery.of(context).size.height,
      color: fc_bg,
      child: Column(
        mainAxisAlignment: MainAxisAlignment.start,
        crossAxisAlignment: CrossAxisAlignment.center,
        children: [
          topBar(),
          Expanded(
            child: Container(
              child: SingleChildScrollView(
                controller: _scrollController,
                physics: BouncingScrollPhysics(),
                scrollDirection: Axis.vertical,
                child: Column(
                  children: [
                    BannerWidget(),
                    bloodDonorWidget(),
                  ],
                ),
              ),
            ),
          ),
        ],
      ),
    );
  }

  topBar() {
    return Container(
      height: sy(120),
      child: Stack(
        children: [
          Positioned(
            child: Container(
              width: Width(context),
              decoration: BoxDecoration(
                color: ThemePrimary,
                borderRadius: BorderRadius.only(
                    bottomRight: Radius.circular(sy(15)),
                    bottomLeft: Radius.circular(sy(15))),
              ),
              padding: EdgeInsets.fromLTRB(sy(10),
                  MediaQuery.paddingOf(context).top + sy(10), sy(5), sy(15)),
              child: Column(
                mainAxisAlignment: MainAxisAlignment.start,
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    children: [
                      Expanded(
                          child: Column(
                        mainAxisAlignment: MainAxisAlignment.start,
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            "Let's Start Exploring The Services !!",
                            style: TextStyle(
                              color: Colors.white,
                              fontWeight: FontWeight.bold,
                              fontSize: sy(ll),
                            ),
                          ),
                          SizedBox(
                            height: sy(3),
                          ),
                          Text(
                            'Empowering Communities Together...',
                            style: TextStyle(
                              color: Colors.white,
                              fontWeight: FontWeight.w400,
                              fontSize: sy(s-1),
                            ),
                          ),
                        ],
                      )),
                      Container(
                        padding: EdgeInsets.all(sy(2)),
                        decoration: decoration_round(
                            fc_stock, sy(4), sy(4), sy(4), sy(4)),
                        child: (gender == 'male')
                            ? Image.asset(
                                'assets/images/user11.jpg',
                                width: sy(30),
                                height: sy(30),
                              )
                            : Image.asset(
                                'assets/images/user16.jpg',
                                width: sy(30),
                                height: sy(30),
                              ),
                      ),
                      SizedBox(
                        width: sy(2),
                      ),
                    ],
                  ),
                ],
              ),
            ),
            top: 0,
            left: 0,
            right: 0,
            bottom: sy(10),
          ),
        ],
      ),
    );
  }

  titleWidget(String label, {GestureTapCallback? onTap}) {
    return Container(
      padding: EdgeInsets.fromLTRB(sy(10), sy(0), sy(10), sy(0)),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceAround,
        crossAxisAlignment: CrossAxisAlignment.center,
        children: [
          Text(
            label,
            style: TextStyle(
                color: fc_1, fontSize: sy(l - 1), fontWeight: FontWeight.w600),
          ),
          Spacer(),
          GestureDetector(
            onTap: onTap,
            child: Container(
              padding: EdgeInsets.fromLTRB(sy(0), sy(0), sy(0), sy(0)),
              child: Row(
                children: [
                  Text(
                    'see more',
                    style: TextStyle(
                      color: fc_1,
                      fontSize: sy(s),
                    ),
                  ),
                  SizedBox(
                    width: sy(2),
                  ),
                  Icon(
                    Icons.arrow_forward_ios,
                    color: fc_3,
                    size: sy(s - 1),
                  )
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }

  BannerWidget() {
    return Container(
      padding: EdgeInsets.fromLTRB(sy(0), sy(0), sy(0), sy(0)),
      child: Column(
        mainAxisAlignment: MainAxisAlignment.start,
        children: [
          titleWidget('UPCOMING ACTIVITIES', onTap: () {}),
          Container(
            child: SingleChildScrollView(
              scrollDirection: Axis.horizontal,
              child: Row(
                children: [
                  for (int i = 0; i < activityDetail.length; i++)
                    Container(
                      margin: EdgeInsets.fromLTRB(sy(0), sy(0), sy(0), sy(0)),
                      child: ElevatedButton(
                        style: ElevatedButton.styleFrom(
                          backgroundColor: fc_bg,
                          //elevation: 2,
                          padding: EdgeInsets.zero,
                          foregroundColor: ThemePrimary,
                          // shape:RoundedRectangleBorder(
                          //   borderRadius:BorderRadius.circular(sy(10)),
                          // ),
                          surfaceTintColor: fc_bg,
                        ),
                        onPressed: () {
                          apiTest(activityDetail.toString());
                        },
                        child: cardBanner(
                            arrayList: activityDetail[i], favBtn: () {}, i: i),
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

  bloodDonorWidget() {
    return Container(
      padding: EdgeInsets.fromLTRB(sy(0), sy(10), sy(0), sy(0)),
      child: Column(
        children: [
          titleWidget('BLOOD REQUIREMENT', onTap: () {}),
          Container(
            child: SingleChildScrollView(
              scrollDirection: Axis.vertical,
              child: Column(
                mainAxisAlignment: MainAxisAlignment.start,
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  for (int i = 0; i < bloodDetail.length; i++)
                    Container(
                      padding: EdgeInsets.fromLTRB(sy(0), sy(0), sy(0), sy(0)),
                      margin: EdgeInsets.fromLTRB(sy(0), sy(0), sy(0), sy(0)),
                      child: ElevatedButton(
                        style: ElevatedButton.styleFrom(
                          backgroundColor: fc_bg,
                          // elevation: 2,
                          padding: EdgeInsets.zero,
                          foregroundColor: ThemePrimary,
                          shape: RoundedRectangleBorder(
                            borderRadius: BorderRadius.circular(sy(0)),
                          ),
                          surfaceTintColor: fc_bg,
                        ),
                        onPressed: () {
                          apiTest(bloodDetail.toString());
                        },
                        child: cardBlood(
                            arrayList: bloodDetail[i], favBtn: () {}, i: i),
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
}
